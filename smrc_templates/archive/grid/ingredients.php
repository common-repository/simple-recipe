<?php
/**
 * @var $recipe
 */

if (!empty($recipe['ingredients'])): ?>
    <div class="smrc_grid_recipe__ingredients terms terms-ingredient">
        <?php echo ($recipe['ingredients']); ?>
    </div>
<?php endif;