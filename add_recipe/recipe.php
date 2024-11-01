<?php

new SMRC_Recipe;

class SMRC_Recipe
{

    public $data;

    public $id;
    public $title;
    public $content;
    public $image;
    public $categories;
    public $meta;
    public $ingredients;
    public $steps;
    public $edit;

    public function __construct($custom_data = array())
    {
        add_action('wp_ajax_smrc_add_recipe', array($this, 'check_required_data'));

        add_action('template_redirect', array($this, 'taxonomy_redirect'));

        if(!empty($custom_data)) {
            $this->post_id();
            $this->sanitize_data();
            $this->init();
        }
    }

    function taxonomy_redirect() {
        $queried_object = get_queried_object();

        $taxonomies = array(
            'smrc_recipe_category',
            'smrc_ingredients',
        );

        $taxonomy = (!empty($queried_object->taxonomy)) ? $queried_object->taxonomy : '';

        if(in_array($taxonomy, $taxonomies)) {
            wp_redirect(add_query_arg("smrc_{$taxonomy}", $queried_object->term_id, SMRC_Archive::archive_url()), 301);
        }

    }

    function check_required_data()
    {
        check_ajax_referer('smrc_add_recipe', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json(array(
                'error' => esc_html__('You should be logged in', 'simple-recipe')
            ));
        }

        /*Get All data*/
        $this->data = json_decode(file_get_contents('php://input'), true);

        /*Check required fields*/
        foreach ($this->required_fields() as $field) {
            if (empty($this->data[$field])) {
                wp_send_json(array(
                    'error' => esc_html__('Please fill all fields', 'simple-recipe')
                ));
            }
        }

        if (!empty($this->data['id'])) $this->check_author($this->data['id']);


        /*START*/
        $this->post_id();
        $this->sanitize_data();
        $this->init();

        wp_send_json(array(
            'success' => esc_html__('Your recipe is on moderation mode.', 'simple-recipe')
        ));

    }

    function check_author($post_id)
    {
        if (!empty($post_id)) {
            $post_author_id = get_post_field('post_author', intval($post_id));

            if (get_current_user_id() !==  intval($post_author_id)) {
                wp_send_json(array(
                    'error' => esc_html__('Its not your recipe. Dont cheat please', 'simple-recipe')
                ));
            }

        }
    }

    function post_id()
    {
        $this->id = (!empty($this->data['id'])) ? intval($this->data['id']) : 0;
    }

    function sanitize_data()
    {
        $this->title = sanitize_text_field($this->data['title']);
        $this->image = intval($this->data['image']);
        $this->content = wp_kses_post($this->data['content']);

        /*Non required fields*/
        $this->categories = (isset($this->data['categories'])) ? $this->data['categories'] : array();
        $this->ingredients = (isset($this->data['ingredients'])) ? $this->data['ingredients'] : array();
        $this->steps = (isset($this->data['steps'])) ? $this->data['steps'] : array();
    }

    function init()
    {
        $this->post();
        $this->meta();
        $this->categories();
        $this->image();
        $this->ingredients();
        $this->steps();

        SMRC_Helpers::send_email(
            'admin',
            esc_html__('New recipe.', 'simple-recipe'),
            esc_html__('New recipe added to your site. Check pending recipes for publishing', 'simple-recipe')
        );
    }

    function post($post_id = '')
    {
        $this->id = wp_insert_post(array(
            'post_type' => 'simple-recipe',
            'post_status' => 'pending',
            'post_author' => get_current_user_id(),
            'ID' => $this->id,
            'post_title' => $this->title,
            'post_content' => $this->content,
        ));
    }

    function meta()
    {

        foreach ($this->meta_fields() as $meta_field) {

            if (!isset($this->data[$meta_field])) continue;

            update_post_meta($this->id, $meta_field, sanitize_text_field($this->data[$meta_field]));

        }
    }

    function image()
    {
        set_post_thumbnail($this->id, $this->data['image']);
    }

    function categories()
    {
        pa($this->id);
        pa($this->categories);

        wp_set_object_terms($this->id, $this->categories, 'smrc_recipe_category');
    }

    function ingredients()
    {
        if (!empty($this->ingredients)) {

            foreach ($this->ingredients as &$ingredient) {

                if (empty($ingredient['type'])) continue;

                $term = wp_insert_term($ingredient['type'], 'smrc_ingredients');

                if (!is_wp_error($term)) {
                    $ingredient['term_id'] = $term['term_id'];
                } else {
                    $term = $term->error_data;
                    if (!empty($term['term_exists'])) $ingredient['term_id'] = $term['term_exists'];
                }

                if (!empty($ingredient['quantity'])) update_post_meta($this->id, "smrc_ingredient_{$ingredient['term_id']}_q", sanitize_text_field($ingredient['quantity']));
                if (!empty($ingredient['quantity_type'])) update_post_meta($this->id, "smrc_ingredient_{$ingredient['term_id']}_q_type", sanitize_text_field($ingredient['quantity_type']));

            }



            wp_set_object_terms($this->id, wp_list_pluck($this->ingredients, 'term_id'), 'smrc_ingredients');


        }
    }

    function steps()
    {
        if (!empty($this->steps)) {
            $steps = array();

            foreach ($this->steps as $step) {

                $step_image = $step_description = '';

                if (!empty($step['image']) and !empty($step['image']['id'])) $step_image = intval($step['image']['id']);
                if (!empty($step['content'])) $step_description = wp_kses_post($step['content']);

                $steps[] = compact('step_image', 'step_description');
            }

            update_post_meta($this->id, 'recipe_steps', json_encode($steps));
        }
    }

    static function meta_fields()
    {
        return array(
            'cooking_time',
            'servings',
            'difficulty',
            'calories',
            'fat',
            'carbohydrates',
            'protein',
            'video_url'
        );
    }

    function required_fields()
    {
        return array(
            'title',
            'image',
            'content',
        );
    }

    public static function edit_recipe()
    {

        $recipe_data = array();
        if (!empty($_GET['edit'])) {
            $post_id = intval($_GET['edit']);
            if (!empty($post_id)) {
                $post = get_post($post_id);
                $author_id = $post->post_author;
                if (intval($author_id) === get_current_user_id()) {
                    $metas = SMRC_Recipe::meta_fields();
                    if (!empty($post)) {
                        $recipe_data['post_title'] = $post->post_title;
                        $recipe_data['post_content'] = $post->post_content;
                        $categories = get_the_terms($post_id, 'smrc_recipe_category');
                        $ingredients = get_the_terms($post_id, 'smrc_ingredients');
                        $categories_data = array();
                        $ingredients_data = array();
                        $recipe_data['id'] = $post_id;
                        if (!empty($categories)) {
                            foreach ($categories as $category) {
                                $categories_data[] = $category->term_id;
                            }
                        }
                        if (!empty($ingredients)) {
                            foreach ($ingredients as $ingredient) {
                                $ingredient_id = $ingredient->term_id;
                                $ingredient_quantity = get_post_meta($post_id, "smrc_ingredient_{$ingredient_id}_q", true);
                                $ingredient_quantity_type = get_post_meta($post_id, "smrc_ingredient_{$ingredient_id}_q_type", true);
                                $ingredients_data[] = array(
                                    'type' => $ingredient->name,
                                    'quantity' => $ingredient_quantity,
                                    'quantity_type' => $ingredient_quantity_type
                                );

                            }
                        }
                        $recipe_data['categories'] = $categories_data;
                        $recipe_data['ingredients'] = $ingredients_data;
                        if (has_post_thumbnail($post_id)) {
                            $recipe_image = array(
                                'id' => get_post_thumbnail_id($post_id),
                                'url' => get_the_post_thumbnail_url($post_id, 'full'),
                                'thumbnail' => get_the_post_thumbnail_url($post_id, 'thumbnail'),
                            );
                            $recipe_data['image'] = $recipe_image;
                        }

                        foreach ($metas as $meta) {
                            $meta_value = get_post_meta($post_id, $meta, true);
                            $recipe_data['metas'][$meta] = $meta_value;
                        }
                        $steps = get_post_meta($post_id, 'recipe_steps', true);
                        $steps_data = array();
                        if (!empty($steps)) {
                            $steps = json_decode($steps);
                            foreach ($steps as $step) {
                                $steps_data[] = array(
                                    'image' => array(
                                        'id' => $step->step_image,
                                        'url' => wp_get_attachment_image_url($step->step_image, 'full'),
                                        'thumbnail' => wp_get_attachment_image_url($step->step_image, 'thumbnail'),
                                    ),
                                    'content' => $step->step_description
                                );
                            }
                        }
                        $recipe_data['steps'] = $steps_data;

                    }
                }
            }
        }

        return $recipe_data;
    }


}