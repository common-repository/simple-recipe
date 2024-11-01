<?php
$post_id = get_the_ID();
$ingredients = get_the_terms($post_id, 'smrc_ingredients');
$calories = get_post_meta($post_id, 'calories', true);
$fat = get_post_meta($post_id, 'fat', true);
$carbohydrates = get_post_meta($post_id, 'carbohydrates', true);
$protein = get_post_meta($post_id, 'protein', true);
if (!empty($ingredients)): ?>
    <div class="smrc_ingredients">
        <h3><?php esc_html_e('Ingredients', 'simple_recipe'); ?></h3>
        <?php foreach ($ingredients as $ingredient): ?>
        <?php
            $qty = get_post_meta($post_id, 'smrc_ingredient_' . $ingredient->term_id . '_q', true);
            $qty_type = get_post_meta($post_id, 'smrc_ingredient_' . $ingredient->term_id . '_q_type', true);
            ?>
            <div class="ingredient">
                <span class="custom_checkbox"></span>
                <span class="value">
                    <?php
                    echo esc_html($ingredient->name . ' ');
                    echo !empty($qty) ? esc_html($qty . ' ') : '';
                    echo !empty($qty_type) ? esc_html($qty_type) : '';
                    ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="ingredients_toggle">
        <i class="smrcicon051-toque"></i>
        <span>
            <?php esc_html_e('Ingredients', 'simple_recipe'); ?>
        </span>
    </div>
<?php endif; ?>

<?php if(!empty($calories) || !empty($fat) || !empty($carbohydrates) || !empty($protein)): ?>
    <div class="recipe_params">
        <h3><?php esc_html_e('Nutrition', 'simple_recipe'); ?></h3>
        <?php if(!empty($calories)): ?>
            <div class="single-param">
                <span class="param-label"><?php esc_html_e('Total calories', 'simple_recipe'); ?></span>
                <span class="param-value"><?php echo esc_html($calories); ?></span>
            </div>
        <?php endif; ?>
        <?php if(!empty($fat)): ?>
            <div class="single-param">
                <span class="param-label"><?php esc_html_e('Total fat', 'simple_recipe'); ?></span>
                <span class="param-value"><?php echo esc_html($fat); ?></span>
            </div>
        <?php endif; ?>
        <?php if(!empty($carbohydrates)): ?>
            <div class="single-param">
                <span class="param-label"><?php esc_html_e('Total carbohydrates', 'simple_recipe'); ?></span>
                <span class="param-value"><?php echo esc_html($carbohydrates); ?></span>
            </div>
        <?php endif; ?>
        <?php if(!empty($protein)): ?>
            <div class="single-param">
                <span class="param-label"><?php esc_html_e('Total protein', 'simple_recipe'); ?></span>
                <span class="param-value"><?php echo esc_html($protein); ?></span>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
