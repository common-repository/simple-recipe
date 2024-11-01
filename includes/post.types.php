<?php

new SMRC_POST_TYPE;

class SMRC_POST_TYPE
{

    function __construct()
    {
        add_action('init', array($this, 'register_post_type'), 10);
    }

    function register_post_type()
    {

        $post_types = $this->types();

        foreach ($post_types as $post_type => $post_type_info) {

            $add_args = (!empty($post_type_info['args'])) ? $post_type_info['args'] : array();

            $args = $this->post_type_args(
                $this->labels($post_type_info['single'],
                    $post_type_info['plural']
                ),
                $post_type,
                $add_args
            );

            register_post_type($post_type, $args);

        }
    }

    function types()
    {
        $posts = apply_filters('simple_recipies_post_types', array(
            'simple-recipe' => array(
                'single' => esc_html__('Recipe', 'simple_recipe'),
                'plural' => esc_html__('Recipes', 'simple_recipe'),
                'args' => array(
                    'public' => true,
                    'publicly_queryable' => true,
                    'show_in_menu' => true,
                    'menu_icon' => SMRC_URL . '/assets/images/lnr-dinner.svg',
                    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions'),
                    'show_ui'            => true,
                    'query_var'          => true,
                    'rewrite'            => array('slug' => 'recipes'),
                    'capability_type'    => 'post',
                    'has_archive'        => true,
                    'hierarchical'       => false,
                )
            ),
        ));

        return $posts;
    }

    function labels($singular, $plural)
    {
        $admin_bar_name = (!empty($admin_bar_name)) ? $admin_bar_name : $plural;
        return array(
            'name' => _x(sprintf('%s', $plural), 'post type general name', 'simple_recipe'),
            'singular_name' => sprintf(_x('%s', 'post type singular name', 'simple_recipe'), $singular),
            'menu_name' => _x(sprintf('%s', $plural), 'admin menu', 'simple_recipe'),
            'name_admin_bar' => sprintf(_x('%s', 'Admin bar ' . $singular . ' name', 'simple_recipe'), $admin_bar_name),
            'add_new_item' => sprintf(__('Add New %s', 'simple_recipe'), $singular),
            'new_item' => sprintf(__('New %s', 'simple_recipe'), $singular),
            'edit_item' => sprintf(__('Edit %s', 'simple_recipe'), $singular),
            'view_item' => sprintf(__('View %s', 'simple_recipe'), $singular),
            'all_items' => sprintf(_x('%s', 'Admin bar ' . $singular . ' name', 'simple_recipe'), $admin_bar_name),
            'search_items' => sprintf(__('Search %s', 'simple_recipe'), $plural),
            'parent_item_colon' => sprintf(__('Parent %s:', 'simple_recipe'), $plural),
            'not_found' => sprintf(__('No %s found.', 'simple_recipe'), $plural),
            'not_found_in_trash' => sprintf(__('No %s found in Trash.', 'simple_recipe'), $plural),
        );
    }

    function post_type_args($labels, $slug, $args = array())
    {
        $can_edit = (current_user_can('edit_posts'));
        $default_args = array(
            'labels' => $labels,
            'public' => $can_edit,
            'publicly_queryable' => $can_edit,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $slug),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title')
        );

        return wp_parse_args($args, $default_args);
    }

}