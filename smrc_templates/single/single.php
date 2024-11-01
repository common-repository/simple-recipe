<?php
SMRC_Enqueue::add_style('single_recipe');
get_header();
?>

    <div class="smrc-recipe">
        <div class="smrc_container">
            <div class="smrc_row">
                <div class="smrc_col-lg-9">
                    <?php do_action('smrc_single_title'); ?>
                    <?php do_action('smrc_single_tags'); ?>
                    <?php do_action('smrc_single_info'); ?>
                    <?php do_action('smrc_single_image'); ?>
                    <?php do_action('smrc_single_description'); ?>
                    <?php do_action('smrc_single_video'); ?>
                    <?php do_action('smrc_single_steps'); ?>
                    <?php do_action('smrc_single_rate_form'); ?>
                    <?php do_action('smrc_single_related'); ?>
                    <?php do_action('smrc_single_comments'); ?>
                </div>
                <div class="smrc_col-lg-3">
                    <div class="smrc-sidebar">
                        <?php do_action('smrc_single_ingredients'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>