<?php
/**
 * @var $recipe
 */

if (!empty($recipe['title'])): ?>
    <h6 class="smrc_grid_recipe__title">
        <?php echo sanitize_text_field($recipe['title']); ?>
    </h6>
<?php endif;