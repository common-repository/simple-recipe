<div class="smrc_mgb_15">
    <h6 v-html="translations.recipe.select_category"></h6>
    <div class="smrc_add_recipe__main_categories">
        <div class="smrc_add_recipe__main_category"
             @click="addCategory(category)"
             v-for="category in categoriesList"
             :class="{'added' : categories.includes(category['term_id'])}">
            <span v-html="category.name"></span>
        </div>
    </div>
</div>