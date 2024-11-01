<?php
$post = get_post();
$content = $post->post_content;
if (!empty($content)): ?>
    <div class="smrc_single_content">
        <?php echo wp_kses_post($content); ?>
    </div>
<?php endif; ?>

