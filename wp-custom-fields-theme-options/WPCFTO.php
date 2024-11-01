<?php
if (!class_exists('Stylemix_WPCFTO')) {

    define('STM_WPCFTO_FILE', __FILE__);
    define('STM_WPCFTO_PATH', dirname(STM_WPCFTO_FILE));
    define('STM_WPCFTO_URL', plugin_dir_url(STM_WPCFTO_FILE));

    class Stylemix_WPCFTO
    {
        function __construct()
        {

            require_once STM_WPCFTO_PATH . '/metaboxes/metabox.php';
            require_once STM_WPCFTO_PATH . '/taxonomy_meta/metaboxes.php';
            require_once STM_WPCFTO_PATH . '/settings/settings.php';
        }
    }

    new Stylemix_WPCFTO();
}