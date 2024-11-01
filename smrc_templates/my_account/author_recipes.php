<?php
if (!is_user_logged_in()) {
    SMRC_Helpers::load_template('my_account/login');
}
$user_id = get_current_user_id();
if (!empty($user_id)):
    SMRC_Enqueue::add_style('archive/grid');
    $args = array(
        'author' => $user_id,
        'post_status' => 'any'
    );
    $filter = new SMRC_Archive_Filter($args);

    $recipes = $filter->get_recipes();
    $pagination = $filter->pages;
    SMRC_Helpers::load_template('archive/grid_view', compact('recipes'));
    SMRC_Helpers::load_template('archive/pagination', compact('pagination'));
endif; ?>