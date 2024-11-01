<?php
add_filter('stm_wpcfto_boxes', function ($boxes) {

    $boxes['recipe_info'] = array(
        'post_type' => array('simple-recipe'),
        'label' => esc_html__('Recipe info', 'simple_recipe'),
    );

    return $boxes;
});

add_filter('stm_wpcfto_fields', function ($fields) {

    $fields['recipe_info'] = array(

        'tab_1' => array(
            'name' => esc_html__('Recipe info', 'simple_recipe'),
            'fields' => array(
                'cooking_time' => array(
                    'type' => 'number',
                    'label' => esc_html__('Cooking time', 'simple_recipe'),
                ),
                'servings' => array(
                    'type' => 'number',
                    'label' => esc_html__('Servings', 'simple_recipe'),
                ),
                'difficulty' => array(
                    'type' => 'select',
                    'label' => esc_html__('Cooking difficulty', 'simple_recipe'),
                    'options' => array(
                        'easy' => esc_html__('Easy', 'simple_recipe'),
                        'medium' => esc_html__('Medium', 'simple_recipe'),
                        'hard' => esc_html__('Hard', 'simple_recipe')
                    )
                ),
                'calories' => array(
                    'type' => 'number',
                    'label' => esc_html__('Calories', 'simple_recipe'),
                ),
                'fat' => array(
                    'type' => 'number',
                    'label' => esc_html__('Fat (percent)', 'simple_recipe'),
                ),
                'carbohydrates' => array(
                    'type' => 'number',
                    'label' => esc_html__('Carbohydrates (percent)', 'simple_recipe'),
                ),
                'protein' => array(
                    'type' => 'number',
                    'label' => esc_html__('Protein (percent)', 'simple_recipe'),
                ),
                'video_url' => array(
                    'type' => 'text',
                    'label' => esc_html__('Youtube embed link', 'simple_recipe'),
                ),
                'recipe_steps' => array(
                    'type' => 'repeater',
                    'label' => esc_html__('Recipe steps', 'my-domain'),
                    'fields' => array(
                        'step_image' => array(
                            'type' => 'image',
                            'label' => esc_html__('Step image', 'my-domain'),
                        ),
                        'step_description' => array(
                            'type' => 'textarea',
                            'label' => esc_html__('Step description', 'my-domain'),
                        ),
                    ),
                ),
            )
        ),

    );

    return $fields;
});

// Options page
add_filter('wpcfto_options_page_setup', function ($setups) {
    $pages = array();
    $args = array(
        'posts_per_page' => '-1',
        'post_status' => 'publish',
        'post_type' => 'page'
    );
    $q = new WP_Query($args);
    if($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $pages[get_the_ID()] = get_the_title();
        }
        wp_reset_postdata();
    }
    $setup[] = array(
        'option_name' => 'smrc_options',
        'page' => array(
            'page_title' => esc_html__('Simple Recipe Settings', 'simple_recipe'),
            'menu_title' => esc_html__('Simple Recipe', 'simple_recipe'),
            'menu_slug' => 'smrc_options',
            'icon' => 'dashicons-admin-generic',
            'position' => 40,
        ),
        'fields' => array(
            'tab_1' => array(
                'name' => esc_html__('Main', 'simple_recipe'),
                'fields' => array(
                    'add_recipe_page' => array(
                        'type' => 'select',
                        'label' => esc_html__('Add recipe page', 'simple_recipe'),
                        'options' => $pages,
                        'description' => esc_html__('Select "Add recipe" page and put in content the shortcode [smrc_add_recipe_page]', 'simple_recipe')
                    ),
                    'my_account_page' => array(
                        'type' => 'select',
                        'label' => esc_html__('My account page', 'simple_recipe'),
                        'options' => $pages,
                        'description' => esc_html__('Select "My account" page and put in content the shortcode [smrc_add_my_account_page]'),
                    ),
                    'rating_login' => array(
                        'type' => 'checkbox',
                        'label' => esc_html__('Users must be logged in to rate recipe', 'simple_recipe'),
                    ),
                    'ingredients_type' => array(
                        'type' => 'text',
                        'label' => esc_html__('Types of ingredients', 'simple_recipe'),
                        'description' => esc_html__('Add comma-separated types of ingredients. e.g. gram,tablespoon,ounce', 'simple_recipe'),
                    ),
                )
            ),
        )
    );

    return $setup;
});