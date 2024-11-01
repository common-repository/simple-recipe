<?php
$steps = get_post_meta(get_the_ID(), 'recipe_steps', true);

if (!empty($steps)):
    $steps = json_decode($steps); ?>

    <div class="smrc_recipe_steps">
        <?php foreach ($steps as $step): ?>
            <div class="recipe_step">
                <?php
                $image_id = $step->step_image;
                $description = $step->step_description;
                if (!empty($image_id)) {
                    $image = wp_get_attachment_image($image_id, 'smrc_large');
                    if (!empty($image)) {
                        echo wp_kses_post($image);
                    }
                }
                if (!empty($description)):
                    ?>
                    <div class="description <?php echo (empty($image_id)) ? 'no-image' : ''; ?>">
                        <?php echo wp_kses_post($description); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

