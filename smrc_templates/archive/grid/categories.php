<?php
/**
 * @var $recipe
 */

if (!empty($recipe['categories'])): ?>
    <div class="smrc_grid_recipe__categories terms terms-categories">
        <?php echo ($recipe['categories']); ?>
    </div>
<?php endif;