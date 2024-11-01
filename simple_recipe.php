<?php
/*
Plugin Name: Simple Recipe
Plugin URI: http://guru-recipe.com/
Description: Simple Recipe is a simple and convenient WordPress plugin for creating a website with recipes.
Version: 1
Author: Guru Team
License: GPLv2 or later
Text Domain: simple_recipe
*/

if (!defined('ABSPATH')) exit; //Exit if accessed directly

define('SMRC_FILE', __FILE__);
define('SMRC_PATH', dirname(SMRC_FILE));
define('SMRC_URL', plugin_dir_url(SMRC_FILE));

if ( ! is_textdomain_loaded( 'simple_recipe' ) ) {
    load_plugin_textdomain( 'simple_recipe', false, 'languages/simple_recipe' );
}

require_once(SMRC_PATH . '/includes/post.types.php');
require_once(SMRC_PATH . '/includes/taxonomies.php');
require_once(SMRC_PATH . '/wp-custom-fields-theme-options/WPCFTO.php');
require_once(SMRC_PATH . '/includes/BFI_Thumb.php');
require_once(SMRC_PATH . '/includes/enqueue.php');
require_once(SMRC_PATH . '/includes/helpers.php');
require_once(SMRC_PATH . '/includes/fields.php');
require_once(SMRC_PATH . '/includes/widgets.php');
require_once(SMRC_PATH . '/includes/validation/Valitron.Class.php');
require_once(SMRC_PATH . '/add_recipe/add_recipe.php');
require_once(SMRC_PATH . '/single_recipe/single_recipe.php');
require_once(SMRC_PATH . '/archive/archive.php');
require_once(SMRC_PATH . '/my_account/my_account.php');
require_once(SMRC_PATH . '/archive/filter.php');