<div class="smrc_mgb_30">
    <h6 v-html="translations.recipe.select_level"></h6>
    <select v-model="level">
        <option v-for="(lvl, lvl_value) in available_levels" :value="lvl_value" v-html="lvl"></option>
    </select>
</div>