<?php

namespace Concrete\Proposals\Api\Spec;


use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Attribute\Category\FileCategory;
use Concrete\Core\Attribute\Category\PageCategory;
use Concrete\Core\Attribute\Category\UserCategory;
use Concrete\Core\Entity\Express\Entity;
use Concrete\Core\Express\ObjectManager;
use Concrete\Proposals\Api\Attribute\OpenApiSpecifiableInterface;
use Concrete\Proposals\Api\OpenApi\Factory\ExpressEntitySpecFactory;
use Concrete\Proposals\Api\OpenApi\SpecMerger;
use Concrete\Proposals\Api\OpenApi\SpecProperty;
use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;
use OpenApi\Serializer;
use Symfony\Component\HttpFoundation\Response;

class ProposedSpecController implements ApplicationAwareInterface
{

    use ApplicationAwareTrait;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var SpecMerger
     */
    protected $merger;

    public function __construct(ObjectManager $objectManager, SpecMerger $merger)
    {
        $this->objectManager = $objectManager;
        $this->merger = $merger;
    }

    private function addExpressSpec(OpenApi $openApi)
    {
        $objects = $this->objectManager->getEntities();
        foreach ($objects as $object) {
            $factory = new ExpressEntitySpecFactory();
            $spec = $factory->build($object);
            $this->merger->merge($spec, $openApi);
        }

        return $openApi;
    }

    // Goes through all the models like UpdatedFile, etc... and makes sure their schemas
    // have valid, dynamic attributes based on the attributes defined.
    private function addCustomAttributesToModels(OpenApi $openApi)
    {
        $fileAttributes = $this->app->make(FileCategory::class)->getList();
        $pageAttributes = $this->app->make(PageCategory::class)->getList();
        $userAttributes = $this->app->make(UserCategory::class)->getList();
        foreach ($openApi->components->schemas as $schema) {
            if (in_array($schema->schema, ['UpdatedFile', 'UpdatedUser', 'UpdatedPage'])) {

                $propertiesProperty = new SpecProperty('attributes', 'Attributes', 'object');
                switch($schema->schema) {
                    case 'UpdatedFile':
                        $attributesToAdd = $fileAttributes;
                        break;
                    case 'UpdatedUser':
                        $attributesToAdd = $userAttributes;
                        break;
                    case 'UpdatedPage':
                        $attributesToAdd = $pageAttributes;
                        break;
                }

                foreach ($attributesToAdd as $attribute) {
                    $controller = $attribute->getController();
                    if ($controller instanceof OpenApiSpecifiableInterface) {
                        $attributeProperty = $controller->getOpenApiSpecProperty();
                    } else {
                        $attributeProperty = new SpecProperty(
                            $attribute->getAttributeKeyHandle(),
                            $attribute->getAttributeKeyDisplayName(),
                            'string'
                        );
                    }
                    $propertiesProperty->addObjectProperty($attributeProperty);
                }
                $this->merger->mergeProperty($propertiesProperty, $schema);
            }
        }
        return $openApi;
    }

    public function getSpec()
    {
        $r = Generator::scan(
            [
                DIR_PACKAGES . '/concrete_api_proposal_2022/src/Controller',
                DIR_PACKAGES . '/concrete_api_proposal_2022/src/Model',
                DIR_PACKAGES . '/concrete_api_proposal_2022/src/Response'
            ]
        );

        $r = $this->addExpressSpec($r);
        $r = $this->addCustomAttributesToModels($r);
        return $r;
    }

    public function generate()
    {
        $r = $this->getSpec();
        return new Response($r->toYaml());

    }
}
