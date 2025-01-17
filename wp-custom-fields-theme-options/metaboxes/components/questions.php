<div class="stm-curriculum stm-curriculum-questions" v-bind:class="{'loading': loading}">

    <div class="stm-curriculum-list" v-if="items.length > 0">
        <draggable v-model="items">
            <div class="stm-curriculum-single" v-for="(item, item_key) in items" :key="item.id">

                <div class="stm-curriculum-single-open">
                    <i class="lnr" v-bind:class="{'lnr-chevron-down' : !item.opened, 'lnr-chevron-up' : item.opened}"
                       @click="openQuestion(item_key)"></i>
                </div>

                <div class="stm-curriculum-single-name">
                    <input type="text" v-model="items[item_key]['title']"
                           @blur="changeTitle(item.id, items[item_key]['title'], item_key)">
                </div>

                <div class="stm-curriculum-single-actions">
                    <div class="stm_lms_answers_container__delete"
                         @click="confirmDelete(item_key, '<?php esc_html_e('Do you really want to delete this item?', 'wp-custom-fields-theme-options') ?>')">
                        <i class="lnr lnr-trash"></i>
                    </div>
                    <a target="_blank"
                       v-bind:href="'<?php echo esc_url(admin_url()); ?>post.php?post=' + item.id + '&action=edit'"
                       class="lnr lnr-pencil"></a>
                </div>

                <transition name="slide-fade">
                    <div class="stm-curriculum-single--meta" v-if="item.opened">

                        <div class="stm-curriculum-single--choices">

                            <label class="stm_lms_radio">
                                <input v-model="items[item_key]['type']" type="radio" value="single_choice">
                                <i></i>
                                <span><?php esc_html_e('Single choice', 'wp-custom-fields-theme-options'); ?></span>
                            </label>

                            <label class="stm_lms_radio">
                                <input v-model="items[item_key]['type']" type="radio" value="multi_choice">
                                <i></i>
                                <span><?php esc_html_e('Multi choice', 'wp-custom-fields-theme-options'); ?></span>
                            </label>

                            <label class="stm_lms_radio">
                                <input v-model="items[item_key]['type']" type="radio" value="true_false">
                                <i></i>
                                <span><?php esc_html_e('True or False', 'wp-custom-fields-theme-options'); ?></span>
                            </label>

                            <label class="stm_lms_radio">
                                <input v-model="items[item_key]['type']" type="radio" value="item_match">
                                <i></i>
                                <span><?php esc_html_e('Item Match', 'wp-custom-fields-theme-options'); ?></span>
                            </label>

                            <label class="stm_lms_radio">
                                <input v-model="items[item_key]['type']" type="radio" value="keywords">
                                <i></i>
                                <span><?php esc_html_e('Keywords', 'wp-custom-fields-theme-options'); ?></span>
                            </label>

                            <label class="stm_lms_radio">
                                <input v-model="items[item_key]['type']" type="radio" value="fill_the_gap">
                                <i></i>
                                <span><?php esc_html_e('Fill the Gap', 'wp-custom-fields-theme-options'); ?></span>
                            </label>

                            <label class="stm_lms_radio">
                                <input v-model="items[item_key]['type']" type="radio" value="question_bank">
                                <i></i>
                                <span><?php esc_html_e('Question Bank', 'wp-custom-fields-theme-options'); ?></span>
                            </label>

                        </div>

                        <stm-answers v-bind:choice="items[item_key]['type']"
                                     v-bind:origin="item_key"
                                     v-bind:stored_answers="items[item_key]['answers']"
                                     v-on:get-answers="items[item_key]['answers'] = $event"></stm-answers>

                    </div>
                </transition>

            </div>
        </draggable>
    </div>

    <div class="stm_lms_question_box">
        <label><?php esc_html_e('Search questions', 'wp-custom-fields-theme-options'); ?></label>
        <div class="searching-container" v-bind:class="{'searching' : searching}">
            <v-select v-model="search"
                      label="title"
                      :options="options"
                      @search="onSearch">
            </v-select>
        </div>

        <div class="container">
            <div class="stm_lms_add_new_question">
                <input type="text"
                       v-model="add_new_question"
                       v-bind:class="{'shake-it': isEmpty}"
                       @keydown.enter.prevent="createQuestion()"
                       v-bind:placeholder="'<?php esc_html_e('Enter question title', 'wp-custom-fields-theme-options'); ?>'"/>
                <a href="#" class="button"
                   @click.prevent="createQuestion()"><?php esc_html_e('Add new question', 'wp-custom-fields-theme-options'); ?></a>
            </div>
        </div>
    </div>

</div>