<h6 v-html="translations.upload_image"></h6>
<?php

$id = (!empty($_GET['edit'])) ? intval($_GET['edit']) : 0;

$image = '';

if(!empty($id) and has_post_thumbnail($id)) {
    $image_id = get_post_thumbnail_id($id);
    $image = array(
        'id' => $image_id,
        'url' => SMRC_Helpers::image_url($image_id),
        'thumbnail' => SMRC_Helpers::image_url($image_id, 'thumbnail')
    );
}

?>
<smrc-upload-image :translations="translations"
                   :uniq="'recipe_image'"
                   :loaded_image='<?php echo json_encode($image); ?>'
                   v-on:get-image="image = $event"></smrc-upload-image>