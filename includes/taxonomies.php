<?php
add_action('init', 'smrc_register_taxonomies');

function smrc_register_taxonomies(){
    register_taxonomy('smrc_recipe_category', array('simple-recipe'), array(
        'hierarchical'  => false,
        'labels'        => array(
            'name'              => esc_html__('Recipes Categories', 'simple_recipe'),
            'singular_name'     => esc_html__( 'Recipe category', 'simple_recipe' ),
            'search_items'      => esc_html__( 'Search recipe category', 'simple_recipe' ),
            'all_items'         => esc_html__( 'All categories', 'simple_recipe' ),
            'edit_item'         => esc_html__( 'Edit category', 'simple_recipe' ),
            'update_item'       => esc_html__( 'Update category', 'simple_recipe' ),
            'add_new_item'      => esc_html__( 'Add New category', 'simple_recipe' ),
            'new_item_name'     => esc_html__( 'New Category', 'simple_recipe' ),
            'menu_name'         => esc_html__( 'Recipes Categories', 'simple_recipe' ),
        ),
        'show_ui'       => true,
        'query_var'     => true,
    ));

    register_taxonomy('smrc_ingredients', array('simple-recipe'), array(
        'hierarchical'  => false,
        'labels'        => array(
            'name'              => esc_html__('Ingredients', 'simple_recipe'),
            'singular_name'     => esc_html__( 'Ingredient', 'simple_recipe' ),
            'search_items'      => esc_html__( 'Search Ingredient', 'simple_recipe' ),
            'all_items'         => esc_html__( 'All Ingredients', 'simple_recipe' ),
            'edit_item'         => esc_html__( 'Edit Ingredient', 'simple_recipe' ),
            'update_item'       => esc_html__( 'Update Ingredient', 'simple_recipe' ),
            'add_new_item'      => esc_html__( 'Add New Ingredient', 'simple_recipe' ),
            'new_item_name'     => esc_html__( 'New Ingredient', 'simple_recipe' ),
            'menu_name'         => esc_html__( 'Ingredients', 'simple_recipe' ),
        ),
        'show_ui'       => true,
        'query_var'     => true,
    ));
}