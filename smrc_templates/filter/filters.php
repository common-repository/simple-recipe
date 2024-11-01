<?php
SMRC_Enqueue::add_style('filters/filter');
?>

<div class="smrc_filter_wrap">

    <form class="smrc_filter_form" action="<?php echo SMRC_Archive::archive_url(); ?>" method="get">

        <?php SMRC_Helpers::load_template('filter/filters/search', compact('recipe')); ?>

        <?php SMRC_Helpers::load_template(
            'filter/filters/autocomplete',
            array(
                'taxonomy' => 'smrc_recipe_category',
                'title' => esc_html__('Select categories', 'simple-recipe'),
                'label' => esc_html__('Type category', 'simple-recipe'),
            )
        ); ?>

        <?php SMRC_Helpers::load_template(
            'filter/filters/autocomplete',
            array(
                'taxonomy' => 'smrc_ingredients',
                'title' => esc_html__('Select ingredients', 'simple-recipe'),
                'label' => esc_html__('Type ingredient', 'simple-recipe'),
            )
        ); ?>

        <input type="hidden" name="recipe_page" value="1">

        <input type="submit" class="btn" value="<?php esc_attr_e('Filter Recipes', 'simple-recipes'); ?>">

    </form>

</div>


