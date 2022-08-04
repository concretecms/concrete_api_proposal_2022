<?php
defined('C5_EXECUTE') or die('Access Denied.');
?>

<h2><?=t('API Documentation & Console')?></h2>

<?=t('Access and test the API using the Swagger interactive API console. Click below to open the API console in a new window.')?>

<div class="help-block"><?=t('Note: You may log out once you open the Swagger interface. It does <em>not</em> require authentication to view the documentation.')?></div>

<div class="text-center mt-4"><a target="_blank" href="<?=URL::to('/ccm/proposals/swagger_ui')?>" class="btn-lg btn btn-primary"><?=t('View API Documentation Console')?></a></div>

<hr>

<h3><?=t('Synchronize Scopes')?></h3>

<p><?=t('The proposed API uses scopes to manage access to things like files and pages. It also uses scopes dynamically generated from Express objects to manage access to Express objects. The scope functionality in the core does not support this, so in order to test the Express API you will need to synchronize scopes using the button below every time you add an Express object you want to test.')?>

<form method="post" action="<?=$view->action('synchronize_scopes')?>">
    <?=$token->output('synchronize_scopes')?>
    <div class="text-center mt-4"><button type="submit" class="btn btn-secondary"><?=t('Synchronize Scopes')?></button></div>
</form>
