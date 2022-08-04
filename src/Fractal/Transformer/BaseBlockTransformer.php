<?php
namespace Concrete\Proposals\Api\Fractal\Transformer;

use Concrete\Core\Block\Block;
use Concrete\Proposals\Api\Attribute\FractalTransformableInterface;
use Concrete\Proposals\Api\Resources;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class BaseBlockTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'page',
    ];

    public function transform(Block $block)
    {
        $controller = $block->getController();
        if ($controller instanceof FractalTransformableInterface) {
            $transformer = $controller->getApiDataTransformer();
            $blockValue = $transformer->transform($block);
        } else {
            // Hacky but a reasonable way to get a default API export
            $exportNode = new \SimpleXMLElement('<temporary-element></temporary-element>');
            $controller->export($exportNode);
            $blockValue = [];
            if (isset($exportNode->data->record)) {
                foreach ($exportNode->data->record->children() as $child) {
                    $blockValue[$child->getName()] = (string) $child;
                }
            }
        }

        return [
            'id' => $block->getBlockID(),
            'type' => $block->getBlockTypeHandle(),
            'value' => $blockValue,
        ];
    }

    public function includePage(Block $block)
    {
        $page = $block->getBlockCollectionObject();
        return new Item($page, new PageTransformer(), Resources::RESOURCE_PAGES);
    }

}
