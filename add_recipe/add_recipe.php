<?php

require_once SMRC_PATH . '/add_recipe/recipe.php';

SMRC_Add_Recipe::getInstance();

class SMRC_Add_Recipe
{

    private static $_instance = null;

    private function __construct()
    {
        add_shortcode('smrc_add_recipe_page', array($this, 'add_recipe_shortcode'));
    }

    protected function __clone()
    {

    }

    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /*Functions*/
    function add_recipe_shortcode($atts) {
        ob_start();
        SMRC_Helpers::load_template('add_recipe/main');
        $content = ob_get_clean();
        return $content;
    }

    static function translations() {
        return array(
            'login' => esc_html__('Login', 'simple_recipe'),
            'enter_login' => esc_html__('Enter login', 'simple_recipe'),
            'enter_name' => esc_html__('Enter name', 'simple_recipe'),
            'enter_last_name' => esc_html__('Enter last name', 'simple_recipe'),
            'enter_password' => esc_html__('Enter password', 'simple_recipe'),
            'enter_re_password' => esc_html__('Enter password again', 'simple_recipe'),
            'enter_email' => esc_html__('Enter email', 'simple_recipe'),
            'submit_login' => esc_html__('Login', 'simple_recipe'),
            'logged_in' => esc_html__('Logged in as:', 'simple_recipe'),
            'registration' => esc_html__('Registration', 'simple_recipe'),
            'submit_register' => esc_html__('Register', 'simple_recipe'),
            'cancel' => esc_html__('Cancel', 'simple_recipe'),
            'required_fields' => esc_html__('Please, fill all fields', 'simple_recipe'),
            'login_warning' => esc_html__('Only logged in users can add recipe', 'simple_recipe'),
            'upload_image' => esc_html__('Upload Image', 'simple_recipe'),
            'loading_image' => esc_html__('Loading image...', 'simple_recipe'),
            'delete_image' => esc_html__('Do you really want to delete image?', 'simple_recipe'),
            'publish' => esc_html__('Save recipe', 'simple_recipe'),
            'loading' => esc_html__('Loading...', 'simple_recipe'),
            'recipe' => array(
                'ingredients' => esc_html__('Ingredients', 'simple_recipe'),
                'dish_info' => esc_html__('Dish Info', 'simple_recipe'),
                'enter_name' => esc_html__('Enter recipe name', 'simple_recipe'),
                'select_category' => esc_html__('Select categories', 'simple_recipe'),
                'select_level' => esc_html__('Cooking difficulty', 'simple_recipe'),
                'enter_cooking_time' => esc_html__('Enter cooking time (minutes)', 'simple_recipe'),
                'enter_servings' => esc_html__('Enter servings (number of persons)', 'simple_recipe'),
                'enter_calories' => esc_html__('Enter calories (kcal)', 'simple_recipe'),
                'enter_fat_percent' => esc_html__('Enter fat percent (%)', 'simple_recipe'),
                'enter_carbohydrates' => esc_html__('Enter Carbohydrates (%)', 'simple_recipe'),
                'enter_protein' => esc_html__('Enter Protein (%)', 'simple_recipe'),
                'enter_youtube_link' => esc_html__('Enter Youtube Embed link', 'simple_recipe'),
                'add_ingredient' => esc_html__('Add Ingredient', 'simple_recipe'),
                'steps' => esc_html__('Steps', 'simple_recipe'),
                'add_step' => esc_html__('Add Step', 'simple_recipe'),
                'enter_ingredient' => esc_html__('Ingredient name', 'simple_recipe'),
                'enter_ingredient_quantity' => esc_html__('Quantity', 'simple_recipe'),
                'enter_description' => esc_html__('Description', 'simple_recipe'),
                'add_warning' => esc_html__('You must be logged in to add recipe', 'simple_recipe'),
                'step_image' => esc_html__('Step image', 'simple_recipe'),
                'step_content' => esc_html__('Step content', 'simple_recipe'),
                'levels' => array(
                    'easy' => esc_html__('Easy', 'simple_recipe'),
                    'medium' => esc_html__('Medium', 'simple_recipe'),
                    'hard' => esc_html__('Hard', 'simple_recipe'),
                ),
                'quantities' => array(
                    'pc' => esc_html__('pieces', 'simple_recipe'),
                    'tablespoon' => esc_html__('tablespoon', 'simple_recipe'),
                    'teaspoon' => esc_html__('teaspoon', 'simple_recipe'),
                    'ounce' => esc_html__('ounce', 'simple_recipe'),
                    'cup' => esc_html__('cup', 'simple_recipe'),
                )
            )
        );
    }

    static function ingredient_types() {
        $settings = get_option('smrc_options', '');
        $ingredient_types = array();
        if(!empty($settings['ingredients_type'])){
            $types = sanitize_text_field($settings['ingredients_type']);
            $types = explode(',', $types);
            if(!empty($types) && is_array($types)){
                $ingredient_types = $types;
            }
        }
        return $ingredient_types;
    }

}