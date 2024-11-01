<?php
/**
 * @var $pagination
 */

if ($pagination['total_pages'] > 1): ?>

    <div class="smrc_pagination">
        <?php foreach ($pagination['pages'] as $page_label => $page_link): ?>
            <?php if (!empty($page_link)):
                $page_classes = array(
                    "smrc_pagination__single",
                    "smrc_pagination__single_{$page_label}"
                );

                if($pagination['current_page'] === $page_label) $page_classes[] = 'current';

                ?>
                <a class="<?php echo esc_attr(implode(' ', $page_classes)) ?>"
                   href="<?php echo esc_url($page_link); ?>">
                    <?php echo sanitize_text_field($page_label); ?>
                </a>
            <?php else: ?>
                <span class="smrc_pagination__single smrc_pagination__single_divider">...</span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

<?php endif;