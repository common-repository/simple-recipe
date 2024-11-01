<?php
    $search = (isset($_GET['search_recipe'])) ? sanitize_text_field($_GET['search_recipe']) : '';
?>

<div class="smrc_filter_field smrc_filter_field__search">
    <h6><?php esc_html_e('Recipe name', 'simple-recipe'); ?></h6>
    <input type="search" name="search_recipe" value="<?php echo esc_attr($search); ?>" placeholder="<?php esc_attr_e('Search recipe', 'simple-recipe'); ?>" />
</div>