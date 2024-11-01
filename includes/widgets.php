<?php

new SMRC_WIDGETS;

class SMRC_WIDGETS
{

    function __construct()
    {
        add_action('widgets_init', array($this, 'register_recipe_sidebar'), 10);
        add_action('widgets_init', array($this, 'register_widget'), 10);
    }

    static function register_recipe_sidebar()
    {
        register_sidebar(array(
            'name' => esc_html__('Simple Recipe Sidebar'),
            'id' => 'smrc_sidebar',
            'description' => '',
            'class' => 'smrc_sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => "</div>\n",
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => "</h2>\n",
        ));
    }

    static function register_widget()
    {
        $widgets = array(
            'smrc_filter'
        );
        foreach ($widgets as $widget) {
            require_once SMRC_PATH . '/widgets/' . $widget . '/main.php';
            register_widget($widget);
        }
    }
}