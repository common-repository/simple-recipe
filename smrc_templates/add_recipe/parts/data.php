<h6 v-html="translations.recipe.dish_info"></h6>

<div class="smrc_mgb_15">
    <input type="number" :placeholder="translations.recipe.enter_cooking_time" v-model="cookingTime">
</div>

<div class="smrc_mgb_15">
    <input type="number" :placeholder="translations.recipe.enter_servings" v-model="servings">
</div>

<div class="smrc_mgb_15">
    <input type="number" :placeholder="translations.recipe.enter_calories" v-model="calories">
</div>

<div class="smrc_mgb_15">
    <input type="number" :placeholder="translations.recipe.enter_fat_percent" v-model="fat">
</div>

<div class="smrc_mgb_15">
    <input type="number" :placeholder="translations.recipe.enter_carbohydrates" v-model="carbohydrates">
</div>

<div class="smrc_mgb_15">
    <input type="number" :placeholder="translations.recipe.enter_protein" v-model="protein">
</div>

<div class="smrc_mgb_15">
    <input type="url" :placeholder="translations.recipe.enter_youtube_link" v-model="youtubeLink">
</div>