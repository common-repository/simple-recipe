<h6 v-html="translations.recipe.ingredients"></h6>

<a href="#" @click.prevent="addIngredient" class="btn btn-small smrc_mgb_15" v-html="translations.recipe.add_ingredient"></a>

<div class="smrc_add_recipe__ingredients">

    <div class="smrc_add_recipe__ingredient" v-for="(ingredient, ingredient_index) in ingredients">

        <div class="smrc_row">

            <div class="smrc_col-md-4">
                <input type="text"
                       v-model="ingredient['type']"
                       :placeholder="translations.recipe.enter_ingredient"/>
            </div>

            <div class="smrc_col-md-4">
                <input type="number"
                       step="0.1"
                       v-model="ingredient['quantity']"
                       :placeholder="translations.recipe.enter_ingredient_quantity"/>
            </div>

            <div class="smrc_col-md-4">
                <select v-model="ingredient['quantity_type']">
                    <option v-for="lvl in available_ingredients_quantities" :value="lvl" v-html="lvl"></option>
                </select>
            </div>

        </div>

        <span class="lnricons-cross" @click="deleteIngredient(ingredient_index)"></span>

    </div>


</div>