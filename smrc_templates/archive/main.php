<?php
/**
 * @var $args
 * @var $per_row
 */

get_header();

$args = (!empty($args)) ? $args : array();
$per_row = (empty($per_row)) ? SMRC_Archive::per_row() : $per_row;


$filter = new SMRC_Archive_Filter($args);
$recipes = $filter->get_recipes();
$pagination = $filter->pages;
SMRC_Enqueue::add_style('archive/grid');

?>

<div class="smrc-archive">

    <div class="smrc_container">

        <div class="smrc_row">

            <div class="<?php echo esc_attr(SMRC_Archive::content_class()); ?>">

                <?php SMRC_Helpers::load_template('archive/grid_view', compact('recipes')); ?>

            </div>

            <?php SMRC_Helpers::load_template('archive/sidebar'); ?>

        </div>

        <?php SMRC_Helpers::load_template('archive/pagination', compact('pagination')); ?>

    </div>

</div>

<?php get_footer(); ?>
