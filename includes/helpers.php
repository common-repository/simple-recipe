<?php

new SMRC_Helpers;

class SMRC_Helpers
{

    function __construct()
    {
        add_action('wp_ajax_smrc_login', 'SMRC_Helpers::login');
        add_action('wp_ajax_nopriv_smrc_login', 'SMRC_Helpers::login');

        add_action('wp_ajax_smrc_register', 'SMRC_Helpers::register');
        add_action('wp_ajax_nopriv_smrc_register', 'SMRC_Helpers::register');

        add_action('wp_head', 'SMRC_Helpers::head');

        add_action('wp_ajax_smrc_upload_image', 'SMRC_Helpers::upload_image');
        add_action('wp_ajax_nopriv_smrc_upload_image', 'SMRC_Helpers::upload_image');

        add_action('init', 'SMRC_Helpers::register_image_sizes');
    }

    public static function send_email($send_to, $subject, $message)
    {
        $send_to = (empty($send_to) || $send_to == 'admin') ? get_option('admin_email') : $send_to;

        add_filter('wp_mail_content_type', 'SMRC_Helpers::set_html_content_type');
        wp_mail($send_to, $subject, $message);
        remove_filter('wp_mail_content_type', 'SMRC_Helpers::set_html_content_type');
    }

    static function set_html_content_type()
    {
        return 'text/html';
    }

    static function register_image_sizes()
    {
        add_image_size('smrc_large', 1200, 900, true);
    }

    static function head()
    {
        $nonces = array(
            'smrc_upload_image',
            'smrc_add_recipe',
            'smrc_rate_recipe'
        );

        $data_nonces = array();

        foreach ($nonces as $nonce) {
            $data_nonces[$nonce] = wp_create_nonce($nonce);
        }

        ?>
        <script>
            var smrc_url = "<?php echo admin_url('admin-ajax.php'); ?>";
            var smrc_nonces = <?php echo json_encode($data_nonces); ?>;
        </script>
    <?php }

    static public function load_template($template_path, $_vars = array())
    {
        extract($_vars);
        include apply_filters("smrc_load_template_{$template_path}", SMRC_PATH . "/smrc_templates/{$template_path}.php");
    }

    /*
     * @param $_POST['login']
     * @param $_POST['password']
     *
    */
    static function login()
    {

        $response = array(
            'error' => false,
            'message' => '',
            'logged_in' => false,
            'user_data' => array()
        );

        $v = new Valitron\Validator($_POST);

        $v->rule('required', array('login', 'password'));

        if (!$v->validate()) {

            $errors = self::get_error($v->errors());

            wp_send_json(array(
                'error' => true,
                'message' => implode(' | ', $errors)
            ));
        }

        $data['user_login'] = sanitize_text_field($_POST['login']);
        $data['user_password'] = sanitize_text_field($_POST['password']);
        $data['remember'] = true;

        $user = wp_signon($data, is_ssl());

        if (is_wp_error($user)) {
            $response['error'] = true;
            $response['message'] = $user->get_error_message();
        } else {
            $response['user_data'] = $user;
            $response['logged_in'] = true;
        }

        wp_send_json($response);

    }

    /*
    * @param $_POST['login']
    * @param $_POST['password']
    * @param $_POST['re_password']
    * @param $_POST['name']
    * @param $_POST['last_name']
    * @param $_POST['email']
    *
   */
    static function register()
    {

        $response = array(
            'error' => false,
            'message' => '',
            'logged_in' => false,
            'user_data' => array()
        );

        $v = new Valitron\Validator($_POST);

        $v->rule('required', array('login', 'password', 're_password', 'name', 'last_name', 'email'));
        $v->rule('email', 'email');

        if (!$v->validate()) {

            $errors = $v->errors();
            $errors_data = [];
            foreach ($errors as $error_types) {
                foreach ($error_types as $error) {
                    $errors_data[] = $error;
                }
            }


            wp_send_json(array(
                'error' => true,
                'message' => implode(' | ', $errors_data)
            ));
        }

        $user_login = sanitize_text_field($_POST['login']);
        $user_password = sanitize_text_field($_POST['password']);
        $user_re_password = sanitize_text_field($_POST['re_password']);
        $user_email = sanitize_text_field($_POST['email']);
        $name = sanitize_text_field($_POST['name']);
        $last_name = sanitize_text_field($_POST['last_name']);

        if ($user_password !== $user_re_password) {
            $response['error'] = true;
            $response['message'] = esc_html__('Passwords do not match', 'simple_recipe');
            wp_send_json($response);
        }

        $user = wp_create_user($user_login, $user_password, $user_email);

        if (is_wp_error($user)) {
            $response['error'] = true;
            $response['message'] = $user->get_error_message();
        } else {
            wp_signon(compact('user_login', 'user_password'), is_ssl());

            $display_name = "{$name} {$last_name}";

            update_user_meta($user, 'first_name', $name);
            update_user_meta($user, 'last_name', $last_name);
            update_user_meta($user, 'display_name', $display_name);

            $user_data = get_userdata($user);
            $user_data->data->display_name = $display_name;
            $response['user_data'] = $user_data;
            $response['logged_in'] = true;
        }

        wp_send_json($response);

    }

    static function get_terms($taxonomy, $args = array())
    {
        $terms = get_terms($taxonomy, $args);

        return $terms;
    }

    static function upload_image()
    {

        check_ajax_referer('smrc_upload_image', 'nonce');

        $file = $_FILES['file'];

        if (empty($_FILES['file'])) {
            wp_send_json(array(
                'error' => esc_html__('Please add image', 'simple_recipe')
            ));
        }

        $allowed_extensions = array(
            'jpg', 'jpeg', 'png',
        );

        $path = $file['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (!in_array($ext, $allowed_extensions)) {
            wp_send_json(array(
                'error' => esc_html__('Invaild image extension. Load only .jpg, .jpeg and .png', 'simple_recipe')
            ));
        }

        $path = $file['name'];

        $filename = basename($path);

        $upload_file = wp_upload_bits($filename, null, file_get_contents($file['tmp_name']));

        if ($upload_file['error']) {
            wp_send_json(array(
                'error' => $upload_file['error']
            ));
        }

        $wp_filetype = wp_check_filetype($filename, null);

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_excerpt' => 'smrc_recipe_image',
            'post_status' => 'inherit',
        );

        $attachment_id = wp_insert_attachment($attachment, $upload_file['file']);

        if (is_wp_error($attachment_id)) {
            wp_send_json(array(
                'error' => $attachment_id->get_error_message()
            ));
        }

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        wp_send_json(array(
            'id' => $attachment_id,
            'url' => wp_get_attachment_url($attachment_id),
            'thumbnail' => self::image_url($attachment_id, 'thumbnail')
        ));
    }

    static function get_error($errors)
    {
        $errors_data = [];
        foreach ($errors as $error_types) {
            foreach ($error_types as $error) {
                $errors_data[] = $error;
            }
        }

        return $errors;
    }

    static function image($image_id, $width, $height)
    {

        $params = compact('width', 'height');

        $image = self::image_url($image_id);

        return bfi_thumb($image, $params);
    }

    static function image_url($image_id, $size = 'full')
    {
        $image = wp_get_attachment_image_src($image_id, $size);

        $image = ($image) ? $image[0] : '';

        return $image;
    }

    static function term_links($data, $divider = '') {
        $links = array();
        foreach($data as $term) {
            $url = get_term_link($term);
            $links[] = "<a href='{$url}' target='_blank'>{$term->name}</a>";
        }

        return implode($divider, $links);
    }

}

function pa($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}