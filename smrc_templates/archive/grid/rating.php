<?php
if(!empty($recipe['id'])):
    $rating = get_post_meta($recipe['id'], 'smrc_rating', 'true');
    if(empty($rating)) {
        $rating = array(
            'value' => 0,
            'votes' => 0
        );
    }
    $rating_value = intval($rating['value']);
    $rating_votes = intval($rating['votes']);
    if(empty($rating_value)) $rating_value = 0;
    $rating_percent = 0;
    if(!empty($rating_votes) && !empty($rating_value)){
        $rating_value = $rating_value / $rating_votes;
        $rating_percent = $rating_value * 100 / 5;
    }
    ?>
    <div class="smrc_rating">
        <div class="rating">
            <div class="rating-wrap"></div>
            <div class="rating-wrap filled" style="width: <?php echo esc_attr($rating_percent); ?>%"></div>
        </div>
        <span class="votes">(<?php echo esc_html($rating_votes); ?>)</span>
    </div>

<?php endif; ?>
