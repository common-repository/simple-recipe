<?php
/**
 * @var $recipe
 */
?>

<div class="smrc_grid_recipe__image">
    <?php if (!empty($recipe['image'])): ?>
        <img src="<?php echo esc_url($recipe['image']); ?>"
             alt="<?php esc_attr_e('Recipe image', 'simple-recipe'); ?>"/>
    <?php else: ?>
        <div class="smrc_grid_recipe__image_empty"></div>
    <?php endif; ?>
</div>