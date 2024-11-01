<?php
$id = get_the_ID();
$servings = get_post_meta($id, 'servings', true);
$cooking_time = get_post_meta($id, 'cooking_time', true);
$difficulty = get_post_meta($id, 'difficulty', true);
?>
<div class="smrc_single_recipe_info">
    <?php if(!empty($servings)): ?>
    <div class="info__detail">
        <div class="detail-wrap">
            <div class="info_label"><?php esc_html_e('Servings', 'simple_recipe'); ?></div>
            <i class="smrcicon022-crossed-knife-and-fork"></i>
            <span class="info-title"><?php echo esc_html($servings); ?></span>
        </div>
    </div>
    <?php endif; ?>
    <?php if(!empty($cooking_time)): ?>
    <div class="info__detail">
        <div class="detail-wrap">
            <div class="info_label"><?php esc_html_e('Cooking time', 'simple_recipe'); ?></div>
            <i class="lnricons-clock"></i>
            <span class="info-title"><?php echo esc_html($cooking_time) . ' ' . esc_html__('min', 'simple_recipe'); ?></span>
        </div>
    </div>
    <?php endif; ?>
    <?php if(!empty($difficulty)): ?>
    <div class="info__detail">
        <div class="detail-wrap">
            <div class="info_label"><?php esc_html_e('Difficulty', 'simple_recipe'); ?></div>
            <i class="smrcicon081-bell-covering-hot-dish"></i>
            <span class="info-title"><?php echo esc_html($difficulty); ?></span>
        </div>
    </div>
    <?php endif; ?>
</div>