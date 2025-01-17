<?php

if (!defined('ABSPATH')) exit; //Exit if accessed directly


class STM_Metaboxes
{

    function __construct()
    {

        require_once STM_WPCFTO_PATH . '/helpers/helpers.php';

        add_action('add_meta_boxes', array($this, 'stm_lms_register_meta_boxes'));

        add_action('admin_enqueue_scripts', array($this, 'stm_lms_scripts'));

        add_action('save_post', array($this, 'stm_lms_save'), 10, 3);

        add_action('wp_ajax_stm_curriculum', array($this, 'stm_search_posts'));

        add_action('wp_ajax_stm_manage_posts', array($this, 'manage_posts'));

        add_action('wp_ajax_stm_lms_change_post_status', array($this, 'change_status'));

        add_action('wp_ajax_stm_curriculum_create_item', array($this, 'stm_curriculum_create_item'));

        add_action('wp_ajax_stm_curriculum_get_item', array($this, 'stm_curriculum_get_item'));

        add_action('wp_ajax_stm_save_questions', array($this, 'stm_save_questions'));

        add_action('wp_ajax_stm_save_title', array($this, 'stm_save_title'));
    }

    function boxes()
    {
        return apply_filters('stm_wpcfto_boxes', array());
    }

    static function get_users()
    {
        $users = array(
            '' => esc_html__('Choose User', 'wp-custom-fields-theme-options')
        );

        if (!is_admin()) return $users;

        $users_data = get_users();
        foreach ($users_data as $user) {
            $users[$user->ID] = $user->data->user_nicename;
        }

        return $users;
    }

    function fields()
    {
        return apply_filters('stm_wpcfto_fields', array());
    }

    function get_fields($metaboxes)
    {

        $fields = array();

        foreach ($metaboxes as $metabox_name => $metabox) {
            foreach ($metabox as $section) {
                foreach ($section['fields'] as $field_name => $field) {

                    $sanitize = (!empty($field['sanitize'])) ? $field['sanitize'] : 'stm_lms_save_field';

                    $fields[$field_name] = !empty($_POST[$field_name]) ? call_user_func(array($this, $sanitize), $_POST[$field_name], $field_name) : '';

                }
            }
        }

        return $fields;
    }

    function stm_lms_save_field($value)
    {
        return $value;
    }

    function stm_lms_save_number($value)
    {
        return floatval($value);
    }

    function stm_lms_sanitize_curriculum($value)
    {
        $value = str_replace('stm_lms_amp', '&', $value);
        return sanitize_text_field($value);
    }

    function stm_lms_save_dates($value, $field_name)
    {
        global $post_id;

        $dates = explode(',', $value);

        if (!empty($dates) and count($dates) > 1) {
            update_post_meta($post_id, $field_name . '_start', $dates[0]);
            update_post_meta($post_id, $field_name . '_end', $dates[1]);
        }

        return $value;
    }


    function stm_lms_register_meta_boxes()
    {
        $boxes = $this->boxes();
        foreach ($boxes as $box_id => $box) {
            $box_name = $box['label'];
            add_meta_box($box_id, $box_name, array($this, 'stm_lms_display_callback'), $box['post_type'], 'normal', 'high', $this->fields());
        }
    }

    function stm_lms_display_callback($post, $metabox)
    {
        $meta = $this->convert_meta($post->ID);
        foreach ($metabox['args'] as $metabox_name => $metabox_data) {
            foreach ($metabox_data as $section_name => $section) {
                foreach ($section['fields'] as $field_name => $field) {
                    $default_value = (!empty($field['value'])) ? $field['value'] : '';
                    $value = (isset($meta[$field_name])) ? $meta[$field_name] : $default_value;
                    if (!empty($value)) {
                        switch ($field['type']) {
                            case 'dates' :
                                $value = explode(',', $value);
                                break;
                            case 'answers' :
                                $value = unserialize($value);
                                break;
                        }
                    }
                    $metabox['args'][$metabox_name][$section_name]['fields'][$field_name]['value'] = $value;
                }
            }
        }
        include STM_WPCFTO_PATH . '/metaboxes/metabox-display.php';
    }

    static function convert_meta($post_id)
    {
        $meta = get_post_meta($post_id);
        $metas = array();
        foreach ($meta as $meta_name => $meta_value) {
            $metas[$meta_name] = $meta_value[0];
        }

        return $metas;
    }

    function stm_lms_scripts($hook)
    {
        $v = time();
        $base = STM_WPCFTO_URL . 'metaboxes/assets/';
        $assets = STM_WPCFTO_URL . 'metaboxes/assets';

        wp_enqueue_media();
        wp_enqueue_script('vue.js', $base . 'js/vue.min.js', array('jquery'), $v);
        wp_enqueue_script('vue-resource.js', $base . 'js/vue-resource.min.js', array('vue.js'), $v);
        wp_enqueue_script('vue2-datepicker.js', $base . 'js/vue2-datepicker.min.js', array('vue.js'), $v);
        wp_enqueue_script('vue-select.js', $base . 'js/vue-select.js', array('vue.js'), $v);
        wp_enqueue_script('vue2-editor.js', $base . 'js/vue2-editor.min.js', array('vue.js'), $v);
        wp_enqueue_script('vue2-color.js', $base . 'js/vue-color.min.js', array('vue.js'), $v);
        wp_enqueue_script('sortable.js', $base . 'js/sortable.min.js', array('vue.js'), $v);
        wp_enqueue_script('vue-draggable.js', $base . 'js/vue-draggable.min.js', array('sortable.js'), $v);
        wp_enqueue_script('stm_lms_mixins.js', $base . 'js/mixins.js', array('vue.js'), $v);
        wp_enqueue_script('stm_lms_metaboxes.js', $base . 'js/metaboxes.js', array('vue.js'), $v);
        wp_enqueue_script('stm-user-search', $base . 'js/stm-user-search.js', array('vue.js'), $v);
//        wp_localize_script('stm-user-search', 'stm_payout_url_data', array(
//            'url' => get_site_url() . STM_LMS_BASE_API_URL,
//        ));

        wp_enqueue_style('stm-lms-metaboxes.css', $base . 'css/main.css', array(), $v);
        wp_enqueue_style('stm-lms-icons', $assets . '/icons/style.css', array(), $v);
        wp_enqueue_style('linear-icons', $base . 'css/linear-icons.css', array('stm-lms-metaboxes.css'), $v);
        wp_enqueue_style('font-awesome-min', $assets . '/vendors/font-awesome.min.css', NULL, $v, 'all');

        /*GENERAL COMPONENTS*/
        $components = array(
            'text',
            'time',
            'number',
            'image',
            'checkbox',
            'date',
            'dates',
            'select',
            'radio',
            'textarea',
            'color',
            'autocomplete',
            'editor',
            'repeater',
        );

        foreach ($components as $component) {
            wp_enqueue_script(
                "wpcfto_{$component}_component",
                STM_WPCFTO_URL . "/metaboxes/general_components/js/{$component}.js",
                array('stm_lms_metaboxes.js'),
                $v,
                true
            );
        }

    }

    function stm_lms_post_types()
    {
        $post_types = array();
        $boxes = $this->boxes();
        if (!empty($boxes)) {
            foreach ($boxes as $box) {
                if (empty($box['post_type'])) continue;
                if (!empty($box['skip_post_type'])) continue;
                $post_types = array_merge($post_types, $box['post_type']);
            }
        }

        $post_types = array_unique($post_types);

        return apply_filters('stm_lms_post_types', $post_types);

    }

    function stm_lms_save($post_id, $post)
    {


        $post_type = get_post_type($post_id);

        if (!in_array($post_type, $this->stm_lms_post_types())) return;

        if (!empty($_POST) and !empty($_POST['action']) and $_POST['action'] === 'editpost') {

            $fields = $this->get_fields($this->fields());


//            stm_pa($fields);
//
//            die;

            foreach ($fields as $field_name => $field_value) {

                update_post_meta($post_id, $field_name, $field_value);
            }
        }


    }

    function stm_search_posts()
    {

        check_ajax_referer('stm_curriculum', 'nonce');

        $r = array();

        $args = array(
            'posts_per_page' => 10,
        );

        if (isset($_GET['ids']) and empty($_GET['ids'])) {
            wp_send_json($r);
        }

        if (!empty($_GET['post_types'])) {
            $args['post_type'] = explode(',', sanitize_text_field($_GET['post_types']));
        }

        if (!empty($_GET['s'])) {
            $args['s'] = sanitize_text_field($_GET['s']);
        }

        if (isset($_GET['ids'])) {
            $args['post__in'] = explode(',', sanitize_text_field($_GET['ids']));
        }

        if (!empty($_GET['exclude_ids'])) {
            $args['post__not_in'] = explode(',', sanitize_text_field($_GET['exclude_ids']));
        }

        if (!empty($_GET['orderby'])) {
            $args['orderby'] = sanitize_text_field($_GET['orderby']);
        }

        if (!empty($_GET['posts_per_page'])) {
            $args['posts_per_page'] = sanitize_text_field($_GET['posts_per_page']);
        }

        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;

        if (!in_array('administrator', $roles)) {
            $args['author'] = get_current_user_id();
        }

        if (!empty($_GET['course_id'])) {
            $course_id = intval($_GET['course_id']);
            $authors = array();
            $authors[] = intval(get_post_field('post_author', $course_id));
            $authors[] = get_post_meta($course_id, 'co_instructor', true);

            $args['author__in'] = $authors;
        }

        $args = apply_filters('stm_lms_search_posts_args', $args);

        /*If somebody applied custom filter just return custom array*/
        if (!empty($_GET['name'])) {
            $name = sanitize_text_field($_GET['name']);
            $r = apply_filters("stm_wpcfto_autocomplete_{$name}", array(), $args);

            if (!empty($args['post__in'])) {

                $data = array();

                foreach ($r as $item) {
                    if (!in_array($item['id'], $args['post__in'])) continue;

                    $data[] = $item;
                }

                $r = $data;
            }

            if (!empty($r)) wp_send_json($r);
        }

        $q = new WP_Query($args);
        if ($q->have_posts()) {
            while ($q->have_posts()) {
                $q->the_post();

                $response = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'post_type' => get_post_type(get_the_ID())
                );

                if (in_array('stm-questions', $args['post_type'])) {
                    $response = array_merge($response, $this->question_fields($response['id']));
                }

                $r[] = $response;
            }

            wp_reset_postdata();
        }

        if(!empty($_GET['ids'])) {
            $insert_sections = array();

            foreach ($args['post__in'] as $key => $post) {
                if (!is_numeric($post)) {
                    $insert_sections[$key] = array('id' => $post, 'title' => $post);
                }
            }

            foreach ($insert_sections as $position => $inserted) {
                array_splice($r, $position, 0, array($inserted));
            }
        }

        wp_send_json($r);
    }

    function get_question_fields()
    {
        return array(
            'type' => array(
                'default' => 'single_choice',
            ),
            'answers' => array(
                'default' => array(),
            ),
            'question' => array(),
            'question_explanation' => array(),
            'question_hint' => array(),
        );
    }

    function question_fields($post_id)
    {
        $fields = $this->get_question_fields();
        $meta = array();

        foreach ($fields as $field_key => $field) {
            $meta[$field_key] = get_post_meta($post_id, $field_key, true);
            $default = (isset($field['default'])) ? $field['default'] : '';
            $meta[$field_key] = (!empty($meta[$field_key])) ? $meta[$field_key] : $default;
        }

        return $meta;
    }

    function stm_curriculum_create_item()
    {

        check_ajax_referer('stm_curriculum_create_item', 'nonce');

        $r = array();
        $available_post_types = array('stm-lessons', 'stm-quizzes', 'stm-questions', 'stm-assignments');

        if (!empty($_GET['post_type'])) $post_type = sanitize_text_field($_GET['post_type']);
        if (!empty($_GET['title'])) $title = sanitize_text_field($_GET['title']);

        /*Check if data passed*/
        if (empty($post_type) and empty($title)) return;

        /*Check if available post type*/
        if (!in_array($post_type, $available_post_types)) return;

        do_action('stm_lms_before_adding_item');

        $item = array(
            'post_type' => $post_type,
            'post_title' => wp_strip_all_tags($title),
            'post_status' => 'publish',
        );

        $r['id'] = wp_insert_post($item);
        $r['title'] = get_the_title($r['id']);
        $r['post_type'] = $post_type;

        if ($post_type == 'stm-questions') {
            $r = array_merge($r, $this->question_fields($r['id']));
        }

        wp_send_json($r);

    }

    function stm_curriculum_get_item()
    {

        $post_id = intval($_GET['id']);
        $r = array();

        $r['meta'] = STM_LMS_Helpers::simplify_meta_array(get_post_meta($post_id));
        if (!empty($r['meta']['lesson_video_poster'])) {
            $image = wp_get_attachment_image_src($r['meta']['lesson_video_poster'], 'img-870-440');
            if (!empty($image[0])) $r['meta']['lesson_video_poster_url'] = $image[0];
        }
        $r['content'] = get_post_field('post_content', $post_id);

        wp_send_json($r);
    }

    function stm_save_questions()
    {

        check_ajax_referer('stm_save_questions', 'nonce');

        $r = array();
        $request_body = file_get_contents('php://input');

        if (!empty($request_body)) {

            $fields = $this->get_question_fields();


            $data = json_decode($request_body, true);

            foreach ($data as $question) {

                if (empty($question['id'])) continue;
                $post_id = $question['id'];

                foreach ($fields as $field_key => $field) {
                    if (!empty($question[$field_key])) {
                        foreach ($question[$field_key] as $index => $value) {
                            $question[$field_key][$index]['text'] = sanitize_text_field($value['text']);
                        }

                        $r[$field_key] = update_post_meta($post_id, $field_key, $question[$field_key]);
                    }
                }
            }
        }
        wp_send_json($r);
    }

    function stm_save_title()
    {

        if (empty($_GET['id']) and !empty($_GET['title'])) return false;

        $post = array(
            'ID' => intval($_GET['id']),
            'post_title' => sanitize_text_field($_GET['title']),
        );

        wp_update_post($post);

        wp_send_json($post);
    }

    function manage_posts()
    {

        check_ajax_referer('stm_manage_posts', 'nonce');

        $r = array(
            'posts' => array()
        );

        $args = array(
            'posts_per_page' => 10,
        );

        if (!empty($_GET['post_types'])) {
            $args['post_type'] = explode(',', sanitize_text_field($_GET['post_types']));
        }

        $args['post_status'] = (!empty($_GET['post_status'])) ? sanitize_text_field($_GET['post_status']) : 'all';
        $offset = (!empty($_GET['page'])) ? intval($_GET['page'] - 1) : 0;
        if (!empty($offset)) $args['offset'] = $offset * $args['posts_per_page'];

        if (!empty($_GET['meta'])) {
            $args['meta_query'] = array(
                array(
                    'key' => sanitize_text_field($_GET['meta']),
                    'compare' => 'EXISTS'
                )
            );
        }


        $r['args'] = $args;

        $q = new WP_Query($args);
        $r['total'] = intval($q->found_posts);
        $r['per_page'] = $args['posts_per_page'];
        $r['offset'] = $args['offset'];

        if ($q->have_posts()) {
            while ($q->have_posts()) {
                $q->the_post();

                $response = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_the_permalink(),
                    'status' => get_post_status(),
                    'edit_link' => get_edit_post_link(get_the_ID(), 'value'),
                    'loading' => false,
                    'loading_text' => ''
                );

                $r['posts'][] = $response;
            }

            wp_reset_postdata();
        }

        wp_send_json($r);
    }

    function change_status()
    {

        check_ajax_referer('stm_lms_change_post_status', 'nonce');

        if (!empty($_GET['post_id']) and !empty($_GET['status'])) {

            remove_action('save_post', array($this, 'stm_lms_save'), 10);
            $post_id = intval($_GET['post_id']);
            $status = sanitize_text_field($_GET['status']);

            $post = array(
                'post_type' => 'stm-courses',
                'ID' => $post_id,
                'post_status' => $status,
            );
            wp_update_post($post);

            add_action('save_post', array($this, 'stm_lms_save'), 10);
            wp_send_json($status);
        }

    }
}

new STM_Metaboxes();

function stm_lms_metaboxes_deps($field, $section_name)
{
    $dependency = '';
    if (empty($field['dependency'])) return $dependency;

    $key = $field['dependency']['key'];
    $compare = $field['dependency']['value'];
    if ($compare === 'not_empty') {
        $dependency = "v-if=data['{$section_name}']['fields']['{$key}']['value']";
    } else {
        $dependency = "v-if=data['{$section_name}']['fields']['{$key}']['value'] == '{$compare}'";
    }

    return $dependency;
}