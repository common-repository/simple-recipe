<?php

new SMRC_Archive;

class SMRC_Archive
{

    static $post_type = 'simple-recipe';

    function __construct()
    {
        add_filter('template_include', array($this, 'smrc_archive_template'));

        add_action('wp_ajax_smrc_get_terms', array($this, 'terms_list'));
        add_action('wp_ajax_nopriv_smrc_get_terms', array($this, 'terms_list'));

        add_shortcode('smrc_recipes_grid', array($this, 'recipes_shortcode'));

    }

    function recipes_shortcode($args) {
        ob_start();
        SMRC_Enqueue::add_style('archive/grid');
        $per_row = (empty($args['per_row'])) ? SMRC_Archive::per_row() : $args['per_row'];
        $filter = new SMRC_Archive_Filter($args);
        $recipes = $filter->get_recipes();
        $hide_title = true;
        SMRC_Helpers::load_template('archive/grid_view', compact('recipes', 'per_row', 'hide_title'));
        return ob_get_clean();
    }

    public static function smrc_archive_template($template)
    {

        if (is_post_type_archive('simple-recipe')) {
            $template = SMRC_PATH . '/smrc_templates/archive/main.php';
        }
        return $template;
    }

    public static function is_active_sidebar()
    {
        return is_active_sidebar('smrc_sidebar');
    }

    public static function per_row()
    {
        return (self::is_active_sidebar()) ? 3 : 4;
    }

    public static function per_row_class($per_row = '')
    {
        $per_row = (!empty($per_row)) ? $per_row : self::per_row();
        $per_row = 12 / $per_row;
        return "smrc_col-lg-{$per_row} smrc_col-md-6 smrc_col-sm-6";
    }

    public static function content_class()
    {

        return (self::is_active_sidebar()) ? 'smrc_col-md-9': 'smrc_col-md-12';

    }

    public static function archive_url()
    {
        return get_post_type_archive_link(self::$post_type);
    }

    function terms_list()
    {
        $r = array();
        if (empty($_GET['taxonomy'])) wp_send_json($r);

        $args = array(
            'taxonomy' => sanitize_text_field($_GET['taxonomy']),
            'number' => 10,
            'hide_empty' => false,
        );

        if (!empty($_GET['terms'])) $args['search'] = sanitize_text_field($_GET['terms']);
        if (!empty($_GET['exclude'])) $args['exclude'] = sanitize_text_field($_GET['exclude']);

        $terms = get_terms($args);
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $r[] = array(
                    'id' => $term->term_id,
                    'label' => $term->name,
                );
            }
        }

        wp_send_json($r);

    }

}