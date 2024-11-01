<h6 v-html="translations.recipe.steps"></h6>

<a href="#" @click.prevent="addStep" class="btn btn-small smrc_mgb_15" v-html="translations.recipe.add_step"></a>


<div class="smrc_add_recipe__steps" v-if="steps.length">
    <div class="smrc_add_recipe__step" v-for="(step, step_index) in steps">
        <div class="smrc_row">

            <div class="smrc_col-md-5">
                <label v-html="translations.recipe.step_image"></label>
                <smrc-upload-image :translations="translations"
                                   :uniq="'recipe_step_image' + step_index"
                                   :key="generateKey()"
                                   :loaded_image="step.image"
                                   v-on:get-image="step.image = $event">
                </smrc-upload-image>
            </div>

            <div class="smrc_col-md-7">
                <label v-html="translations.recipe.step_content"></label>
                <textarea v-model="step.content"></textarea>
            </div>


        </div>


        <span class="lnricons-cross" @click="deleteStep(step_index)"></span>

    </div>
</div>