<?php

defined('C5_EXECUTE') or die('Access Denied.');


?>

<link rel="stylesheet" type="text/css" href="<?=REL_DIR_PACKAGES?>/concrete_api_proposal_2022/swagger/swagger-ui.css" />

<div id="swagger-ui"></div>
<script src="<?=REL_DIR_PACKAGES?>/concrete_api_proposal_2022/swagger/swagger-ui-bundle.js" charset="UTF-8"> </script>
<script src="<?=REL_DIR_PACKAGES?>/concrete_api_proposal_2022/swagger/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>

<script>
    window.onload = function() {
        //<editor-fold desc="Changeable Configuration Block">

        // the following lines will be replaced by docker/configurator, when it runs in a docker-container
        window.ui = SwaggerUIBundle({
            url: "<?=URL::to('/ccm/api/proposed/spec')?>",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            oauth2RedirectUrl: '<?=$oauth2RedirectUrl?>',
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });
        <?php if (isset($clientKey) && isset($clientSecret)) { ?>
            window.ui.initOAuth({
                clientId: "<?=h($clientKey)?>",
                clientSecret: "<?=h($clientSecret)?>"
            })
        <?php } ?>

        //</editor-fold>
    };
</script>
