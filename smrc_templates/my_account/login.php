<?php

SMRC_Enqueue::add_style('add_recipe');
SMRC_Enqueue::add_script('add_recipe', array('vue.js', 'vue-resource.js', 'smrc_login', 'smrc_upload_image'));

wp_localize_script('smrc_add_recipe', 'smrc_login_data', array(
    'logged_in' => is_user_logged_in(),
    'user_data' => get_userdata(get_current_user_id()),
));

wp_localize_script('smrc_add_recipe', 'smrc_add_recipe', array(
    'translations' => SMRC_Add_Recipe::translations()
));

?>

<div id="smrc_add_recipe" class="smrc_add_recipe">

    <smrc-login-register :translations="translations" :warning="translations.login_warning"
                         v-on:login-data="loggedIn = $event"></smrc-login-register>
</div>