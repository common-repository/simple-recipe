<?php
SMRC_Enqueue::add_style('archive/grid');
$post_id = get_the_ID();
$terms = get_the_terms($post_id, 'smrc_recipe_category');
$term_ids = array();
if(!empty($terms)){
    foreach ($terms as $term) {
        $term_ids[] = $term->term_id;
    }
}
$args = array(
    'posts_per_page' => 3,
    'post__not_in' => array($post_id)
);
if(!empty($term_ids)){
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'smrc_recipe_category',
            'field'    => 'id',
            'terms'    => $term_ids
        )
    );
}
$filter = new SMRC_Archive_Filter($args);

$recipes = $filter->get_recipes();
$title = esc_html__('Related recipes', 'simple_recipe');
SMRC_Helpers::load_template('archive/grid_view', compact('recipes', 'title'));