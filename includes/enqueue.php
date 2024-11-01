<?php

new SMRC_Enqueue;

class SMRC_Enqueue
{

    function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
    }

    function enqueue()
    {

        $version = self::version();

        /*Vendor Styles*/

        /*Styles*/
        wp_enqueue_style('smrc_grid', SMRC_URL . '/assets/vendor_css/grid.min.css', null, $version);
        wp_enqueue_style('linearicons', SMRC_URL . '/assets/fonts/linearicons/linear.css', null, $version);
        wp_enqueue_style('smrc-icons', SMRC_URL . '/assets/fonts/icons/eat-icons.css', null, $version);
        wp_enqueue_style('smrc_style', SMRC_URL . '/assets/css/main.css', null, $version);
        wp_enqueue_style('smrc_style_simple', SMRC_URL . '/assets/css/simple_styles.css', null, $version);

        /*Vendor Scripts*/
        wp_register_script('jquery.cookie', SMRC_URL . '/assets/vendor_js/jquery.cookie.js', null, $version, true);
        wp_register_script('vue.js', SMRC_URL . '/assets/vendor_js/vue.min.js', null, $version, true);
        wp_register_script('vue-resource.js', SMRC_URL . '/assets/vendor_js/vue-resource.min.js', array('vue.js'), $version, true);

        /*Component Scripts*/
        wp_register_script('smrc_login', SMRC_URL . '/assets/js/components/login_register.js', null, $version, true);
        wp_register_script('smrc_upload_image', SMRC_URL . '/assets/js/components/upload_image.js', null, $version, true);

        /*Scripts*/
        wp_enqueue_script('smrc_script', SMRC_URL . '/assets/js/main.js', array('jquery', 'jquery.cookie'), $version, true);
    }

    static function add_style($file, $deps = array('smrc_style'))
    {
        $version = self::version();
        wp_enqueue_style("smrc_{$file}", SMRC_URL . "/assets/css/{$file}.css", $deps, $version);
    }

    static function add_script($file, $deps = array('smrc_script'), $in_footer = 'true', $inline_script = '')
    {
        $version = self::version();
        wp_enqueue_script("smrc_{$file}", SMRC_URL . "/assets/js/{$file}.js", $deps, $version, $in_footer);

        if (!empty($inline_script)) {
            wp_add_inline_script("smrc_{$file}", $inline_script);
        }
    }

    static function version() {
        return (WP_DEBUG) ? '1.0' : time();
    }

}