<?php
$post_id = get_the_ID();
if (empty($_COOKIE['smrc_rating_' . $post_id])):
    $settings = get_option('smrc_options', array());
    if (empty($settings['rating_login'])):
        ?>
        <form class="smrc_rating_form" method="post" data-post-id="<?php echo esc_attr($post_id); ?>">
            <h3><?php esc_html_e('Rate recipe', 'simple_recipe'); ?></h3>
            <div class="inputs-wrap active-5">
                <input type="radio" class="rating_radio" name="rating" value="1">
                <input type="radio" class="rating_radio" name="rating" value="2">
                <input type="radio" class="rating_radio" name="rating" value="3">
                <input type="radio" class="rating_radio" name="rating" value="4">
                <input type="radio" class="rating_radio" checked name="rating" value="5">
            </div>
            <button type="submit"><?php esc_html_e('Rate', 'simple_recipe'); ?></button>
        </form>
    <?php
    endif;
endif;