<?php

SMRC_Enqueue::add_style('add_recipe');
SMRC_Enqueue::add_script('add_recipe', array('vue.js', 'vue-resource.js', 'smrc_login', 'smrc_upload_image'));

wp_localize_script('smrc_add_recipe', 'smrc_login_data', array(
    'logged_in' => is_user_logged_in(),
    'user_data' => get_userdata(get_current_user_id()),
));


$edit = SMRC_Recipe::edit_recipe();
wp_localize_script('smrc_add_recipe', 'smrc_add_recipe', array(
    'categories' => SMRC_Helpers::get_terms('smrc_recipe_category', array('hide_empty' => false)),
    'translations' => SMRC_Add_Recipe::translations(),
    'ingredient_types' => SMRC_Add_Recipe::ingredient_types(),
    'edit_recipe' => $edit
));

$content = (!empty($edit['post_content'])) ? $edit['post_content'] : '';

?>

<div id="smrc_add_recipe" class="smrc_add_recipe">

    <div class="smrc_add_recipe" v-if="!success">

        <h3>
            <?php
            if (!empty($_GET['edit'])) {
                esc_html_e('Update recipe', 'simple_recipe');
            } else {
                esc_html_e('Add recipe', 'simple_recipe');
            }
            ?>
        </h3>

        <div class="smrc_add_recipe__main">

            <?php SMRC_Helpers::load_template('add_recipe/parts/title'); ?>

            <?php SMRC_Helpers::load_template('add_recipe/parts/image'); ?>

            <?php SMRC_Helpers::load_template('add_recipe/parts/editor', array('content' => $content)); ?>

            <?php SMRC_Helpers::load_template('add_recipe/parts/steps'); ?>

            <?php SMRC_Helpers::load_template('add_recipe/parts/category'); ?>

            <?php SMRC_Helpers::load_template('add_recipe/parts/data'); ?>

            <?php SMRC_Helpers::load_template('add_recipe/parts/ingredients'); ?>

        </div>

        <hr/>

        <smrc-login-register :translations="translations" :warning="translations.login_warning"
                             v-on:login-data="loggedIn = $event"></smrc-login-register>

        <hr/>

        <a href="#"
           class="btn"
           @click.prevent="addRecipe" v-html="translations.publish"
           v-if="loggedIn && !loading"></a>

        <div class="success message" v-if="loading" v-html="translations.loading"></div>

        <div class="success message" v-if="error" v-html="error"></div>


    </div>

    <div class="success message" v-else v-html="success"></div>

</div>