<?php
/**
 * @var $content
 */

?>

<div class="smrc_mgb_15">
    <h6 v-html="translations.recipe.enter_description"></h6>

    <?php wp_editor($content, 'smrc_recipe_editor'); ?>
</div>