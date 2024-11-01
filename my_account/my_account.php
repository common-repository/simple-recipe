<?php

new SMRC_My_Account;

class SMRC_My_Account
{
    function __construct()
    {
        add_shortcode('smrc_add_my_account_page', array($this, 'add_my_account_page'));
        add_action('smrc_my_account_author_recipes', array($this, 'my_account_author_recipes'));
        add_action('smrc_single_grid_recipe', array($this, 'add_edit_link'), 10, 1);
    }

    static function add_my_account_page()
    {
        ob_start();
        SMRC_Helpers::load_template('my_account/main');
        $content = ob_get_clean();
        return $content;
    }

    static function my_account_author_recipes()
    {
        SMRC_Helpers::load_template('my_account/author_recipes');
    }

    static function add_edit_link($post_id)
    {
        $settings = get_option('smrc_options', array());
        $add_recipe_page = intval($settings['add_recipe_page']);
        $my_account_page = intval($settings['my_account_page']);
        $add_recipe_page_url = '';
        if (!empty($add_recipe_page)) {
            $add_recipe_page = get_post($add_recipe_page);
            if (!empty($add_recipe_page)) {
                $add_recipe_page_url = get_the_permalink($add_recipe_page);
            }
        }
        if (get_the_ID() === $my_account_page && !empty($add_recipe_page_url) && !empty($post_id)):
            ?>
            <a href="<?php echo esc_url(add_query_arg(array('edit' => $post_id), $add_recipe_page_url)); ?>" class="edit_link" title="<?php esc_attr_e('Edit recipe', 'simple_recipe'); ?>">
                <i class="lnricons-pencil"></i>
            </a>
        <?php
        endif;
    }
}