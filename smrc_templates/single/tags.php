<?php
$post_id = get_the_ID();
$terms = get_the_terms($post_id, 'smrc_recipe_category');
if(!empty($terms)): ?>
    <div class="smrc_tags">
        <span class="tags_label">
            <i class="lnricons-tags"></i>
        </span>
        <?php $term_links = array(); ?>
        <?php
        foreach ($terms as $term){
            $term_links[] = '<a href="' . get_term_link($term->term_id) . '">' . esc_html($term->name) . '</a>';
        }
        echo implode(', ', $term_links);
        ?>
    </div>
<?php endif; ?>
