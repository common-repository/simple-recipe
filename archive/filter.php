<?php

class SMRC_Archive_Filter
{

    public $args = array();
    public $recipes = array();
    public $per_page = 12;
    public $pages = array(
        'total_pages' => 1,
        'current_page' => 1,
        'per_page' => 12,
        'pages' => array()
    );

    public function __construct($args = array())
    {

        $this->args = wp_parse_args($args, $this->default_args());
        $this->filters();

    }

    function default_args()
    {
        return array(
            'post_type' => 'simple-recipe',
            'post_status' => 'publish',
            'posts_per_page' => $this->per_page,
            'meta_query' => array(),
            'tax_query' => array(
                'relation' => 'OR'
            ),
        );
    }

    function get_recipes()
    {

        $q = new WP_Query($this->args);

        $this->pagination($q->found_posts);

        if ($q->have_posts()) {
            while ($q->have_posts()) {
                $q->the_post();

                $id = get_the_ID();

                $recipe = array(
                    'id' => $id,
                    'title' => get_the_title(),
                    'content' => get_the_content(),
                    'permalink' => get_the_permalink(),
                    'ingredients' => SMRC_Helpers::term_links(wp_get_post_terms($id, 'smrc_ingredients')),
                    'categories' => SMRC_Helpers::term_links(wp_get_post_terms($id, 'smrc_recipe_category')),
                );

                if (has_post_thumbnail()) {
                    $recipe['image'] = SMRC_Helpers::image(get_post_thumbnail_id($id), 270, 180);
                }

                $this->recipes[] = $recipe;

            }

            wp_reset_postdata();
        }

        return $this->recipes;
    }

    function pagination($total_posts)
    {

        $this->per_page = $this->args['posts_per_page'];
        $this->pages['total_pages'] = ceil($total_posts / $this->pages['per_page']);
        $this->pages['pages'] = $this->get_pagination_links($this->pages['current_page'], $this->pages['total_pages']);


    }

    function get_pagination_links($current_page, $total_pages)
    {
        $pages = array();

        if ($total_pages >= 1 && $current_page <= $total_pages) {

            $pages[1] = add_query_arg('recipe_page', 1, $this->current_url());
            $i = max(2, $current_page - 5);
            if ($i > 2) $pages['...'] = '';

            for (; $i < min($current_page + 6, $total_pages); $i++) {
                $pages[$i] = add_query_arg('recipe_page', $i, $this->current_url());
            }

            if ($i != $total_pages) $pages['...'] = '';

            $pages[$total_pages] = add_query_arg('recipe_page', $total_pages, $this->current_url());
        }

        return $pages;
    }

    function filters()
    {
        if (!empty($_GET)) {

            foreach ($_GET as $get_key => $get_value) {

                if (!method_exists('SMRC_Archive_Filter', $get_key) || !isset($get_value)) continue;

                $this->$get_key($get_value);

            }

        }
    }

    function search_recipe($value)
    {
        $this->args['s'] = sanitize_text_field($value);
    }

    function recipe_page($value)
    {
        $current_page = intval($value);
        $this->pages['current_page'] = $current_page;
        $this->args['offset'] = ($current_page * $this->per_page) - $this->per_page;
    }

    function smrc_smrc_recipe_category($value)
    {
        $term_query = $this->term_query_args($value, 'smrc_recipe_category');
        if(!empty($term_query)) $this->args['tax_query'][] = $term_query;
    }



    function current_url()
    {
        $ssl = (is_ssl()) ? 'https' : 'http';
        return "{$ssl}://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    function term_query_args($values, $taxonomy)
    {
        if(empty($values)) return [];

        $terms = explode(',', $values);
        if(empty($terms)) return [];

        $term_query = array(
            'relation' => 'OR'
        );

        $term_query[] = array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $terms
        );

        return $term_query;

    }

    static function get_current_terms($taxonomy) {
        if(empty($_GET["smrc_{$taxonomy}"])) return [];

        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'include' => sanitize_text_field($_GET["smrc_{$taxonomy}"]),
            'hide_empty' => false
        ));

        if(empty($terms)) return [];

        $r = array();

        foreach($terms as $term) {
            $r[] = array(
                'id' => $term->term_id,
                'label' => $term->name,
            );
        }

        return $r;

    }

}