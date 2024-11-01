<?php

new SMRC_Single_Recipe;

class SMRC_Single_Recipe {
    function __construct()
    {
        add_filter('single_template', array($this, 'smrc_single_template'));
        add_action('smrc_single_image', array($this, 'single_image'));
        add_action('smrc_single_tags', array($this, 'single_tags'));
        add_action('smrc_single_title', array($this, 'single_title'));
        add_action('smrc_single_rating', array($this, 'single_rating'));
        add_action('smrc_single_info', array($this, 'single_info'));
        add_action('smrc_single_description', array($this, 'single_description'));
        add_action('smrc_single_video', array($this, 'single_video'));
        add_action('smrc_single_steps', array($this, 'single_steps'));
        add_action('smrc_single_ingredients', array($this, 'single_ingredients'));
        add_action('smrc_single_comments', array($this, 'comments'));
        add_action('smrc_single_rate_form', array($this, 'rate_form'));
        add_action('smrc_single_related', array($this, 'related'));

        add_action('wp_ajax_smrc_rate_recipe', array($this, 'smrc_rate_recipe'));
        add_action('wp_ajax_nopriv_smrc_rate_recipe', array($this, 'smrc_rate_recipe'));
    }

    public static function smrc_single_template($template)
    {
        global $post;

        if( $post->post_type === 'simple-recipe' ){
            $template = SMRC_PATH . '/smrc_templates/single/single.php';
        }
        return $template;
    }

    static function single_title() {
        SMRC_Helpers::load_template('single/title');
    }

    static function single_image() {
        SMRC_Helpers::load_template('single/image');
    }

    static function single_tags() {
        SMRC_Helpers::load_template('single/tags');
    }

    static function single_info() {
        SMRC_Helpers::load_template('single/info');
    }

    static function single_description() {
        SMRC_Helpers::load_template('single/description');
    }

    static function single_video() {
        SMRC_Helpers::load_template('single/video');
    }

    static function single_steps() {
        SMRC_Helpers::load_template('single/steps');
    }

    static function single_ingredients() {
        SMRC_Helpers::load_template('single/ingredients');
    }

    static function single_rating() {
        SMRC_Helpers::load_template('single/rating');
    }

    static function related() {
        SMRC_Helpers::load_template('single/related');
    }

    static function comments() {
        comments_template();
    }

    static function rate_form() {
        SMRC_Helpers::load_template('single/rating_form');
    }

    static function smrc_rate_recipe() {
        check_ajax_referer('smrc_rate_recipe', 'nonce');
        $post_id = !empty($_POST['post_id']) ? intval($_POST['post_id']) : '';
        $rating = !empty($_POST['rate']) ? intval($_POST['rate']) : '';

        if(!empty($post_id) && !empty($rating)){
            $old_rating = get_post_meta($post_id, 'smrc_rating', true);
            $new_rating = array();
            if(!empty($old_rating)) {
                $value = intval($old_rating['value']);
                $votes = intval($old_rating['votes']);
                if(!empty($value) && !empty($votes)){
                    $new_rating = array(
                        'value' => $value + $rating,
                        'votes' => $votes + 1
                    );
                }
            }
            else {
                $new_rating = array(
                    'value' => $rating,
                    'votes' => 1
                );
            }


            update_post_meta($post_id, 'smrc_rating', $new_rating);

            wp_send_json($new_rating);

        }
    }
}