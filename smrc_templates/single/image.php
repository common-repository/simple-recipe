<?php if(has_post_thumbnail(get_the_ID())): ?>
<div class="smrc_image">
    <?php the_post_thumbnail('smrc_large'); ?>
</div>
<?php endif; ?>
