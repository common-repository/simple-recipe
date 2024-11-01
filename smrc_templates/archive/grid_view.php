<?php
/**
 * @var $recipes
 * @var $per_row
 * @var $hide_title
 * @var $title
 */

$per_row = (empty($per_row)) ? SMRC_Archive::per_row() : $per_row;

if (!empty($recipes)): ?>
    <?php if (empty($hide_title)): ?>
        <h3>
            <?php
            if (!empty($title)) {
                echo esc_html($title);
            } else {
                esc_html_e('Recipes', 'simple-recipe');
            }
            ?>
        </h3>
    <?php endif; ?>

    <div class="smrc_row">

        <?php foreach ($recipes as $recipe): ?>
            <div class="<?php echo esc_attr(SMRC_Archive::per_row_class($per_row)) ?>">
                <?php SMRC_Helpers::load_template('archive/grid/main', compact('recipe')); ?>
            </div>
        <?php endforeach; ?>

    </div>

<?php else : ?>
    <h4><?php printf(esc_html__('Sorry, no recipes found. %sFresh start!%s', 'simple-recipe'), "<a href=" . SMRC_Archive::archive_url() . ">", "</a>"); ?></h4>
<?php endif; ?>
