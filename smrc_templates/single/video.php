<?php
$video_url = get_post_meta(get_the_ID(), 'video_url', true);
if(!empty($video_url)): ?>
    <div class="smrc_recipe_video smrc_embed_wrap">
        <iframe src="<?php echo esc_url($video_url); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
<?php endif;