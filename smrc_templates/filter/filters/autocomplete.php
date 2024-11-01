<?php

/**
 * @var $taxonomy
 * @var $label
 * @var $title
 */

SMRC_Enqueue::add_style('filters/autocomplete');
SMRC_Enqueue::add_script('filter/autocomplete', array('vue.js', 'vue-resource.js'));
wp_localize_script('smrc_filter/autocomplete', "current_{$taxonomy}", SMRC_Archive_Filter::get_current_terms($taxonomy));

?>

<div class="smrc_filter_field smrc_filter_field__autocomplete" data-type="<?php echo esc_attr($taxonomy) ?>">

    <h6><?php echo esc_html($title); ?></h6>

    <div class="smrc_filter_field__autocomplete_choices" v-bind:class="{'loading' : loading}">

        <input type="text"
               @keyup="searchTerms"
               v-model="s"
               class="autocomplete"
               v-on:keydown.enter.prevent=""
               placeholder="<?php echo esc_attr($label); ?>"/>

        <div class="smrc_filter_field__autocomplete_suggestions" v-if="choices.length">
            <div class="smrc_filter_field__autocomplete_suggestion"
                 v-for="choice in choices"
                 v-html="choice.label"
                 @click="selectTerm(choice)">
            </div>
        </div>

    </div>

    <input type="hidden"
           class="hidden_filter"
           v-model="value"
           name="smrc_<?php echo esc_attr($taxonomy); ?>">

    <div class="selected_labels" v-if="tags.length">
        <div class="selected_label" v-for="(tag, tag_index) in tags">
            <span v-html="tag.label"></span>
            <i class="delete lnricons-cross" @click="tags.splice(tag_index, 1)"></i>
        </div>
    </div>

</div>