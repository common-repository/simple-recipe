<?php

/**
 * @var $recipe
 */
?>

<div class="smrc_grid_recipe">

    <div class="smrc_grid_recipe__inner">

        <a href="<?php echo esc_url($recipe['permalink']); ?>">
            <?php SMRC_Helpers::load_template('archive/grid/image', compact('recipe')); ?>
        </a>

        <a href="<?php echo esc_url($recipe['permalink']); ?>">
            <?php SMRC_Helpers::load_template('archive/grid/title', compact('recipe')); ?>
        </a>

        <?php SMRC_Helpers::load_template('archive/grid/categories', compact('recipe')); ?>

        <?php SMRC_Helpers::load_template('archive/grid/ingredients', compact('recipe')); ?>

        <?php SMRC_Helpers::load_template('archive/grid/rating', compact('recipe')); ?>

        <?php do_action('smrc_single_grid_recipe', $recipe['id']); ?>

    </div>

</div>
