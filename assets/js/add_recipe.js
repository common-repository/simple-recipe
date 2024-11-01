'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

Array.prototype.remove = function () {
    var what = void 0,
        a = arguments,
        L = a.length,
        ax = void 0;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

new Vue({

    /**
     * @var smrc_add_recipe
     */

    el: '#smrc_add_recipe',
    data: {
        translations: smrc_add_recipe['translations'],
        available_categories: smrc_add_recipe['categories'],
        available_levels: smrc_add_recipe['translations']['recipe']['levels'],
        available_ingredients_quantities: smrc_add_recipe['ingredient_types'],
        loggedIn: smrc_login_data['logged_in'],

        isTiny: false,

        id: '',
        name: '',
        image: '',
        content: '',
        categories: [],
        level: 'easy',
        cookingTime: '',
        servings: '',
        calories: '',
        fat: '',
        carbohydrates: '',
        protein: '',
        youtubeLink: '',
        ingredients: [],
        steps: [],
        loading: false,

        success: false,
        error: ''
    },
    computed: {
        categoriesList: function categoriesList() {
            return this.available_categories;
        }
    },
    methods: {
        addCategory: function addCategory(category) {
            if (this.categories.includes(category.term_id)) {
                this.categories.remove(category.term_id);
            } else {
                this.categories.push(category.term_id);
            }
        },
        addIngredient: function addIngredient() {
            this.ingredients.push({
                type: '',
                quantity: '',
                quantity_type: 'pc'
            });
        },
        deleteIngredient: function deleteIngredient(index) {
            this.ingredients.splice(index, 1);
        },
        mountTinymce: function mountTinymce() {
            var _this = this;
            var editor = tinyMCE.get('smrc_recipe_editor');

            if (editor !== null) {
                _this.$set(_this, 'content', editor.getContent());

                editor.on('keyup', function (e) {
                    _this.$set(_this, 'content', editor.getContent());
                });

                setTimeout(function () {
                    _this.$set(_this, 'content', editor.getContent());
                }, 500);
            }
        },
        waitForTinyMCE: function waitForTinyMCE() {
            var _this = this;
            if (typeof tinyMCE !== 'undefined') {
                _this.mountTinymce();
            } else {
                setTimeout(function () {
                    _this.waitForTinyMCE();
                }, 500);
            }
        },
        addRecipe: function addRecipe() {
            var _this = this;

            var data = {
                title: _this.name,
                id: _this.id,
                image: _this.image.id,
                content: _this.content,
                categories: _this.categories,
                cooking_time: _this.cookingTime,
                servings: _this.servings,
                difficulty: _this.level,
                calories: _this.calories,
                fat: _this.fat,
                carbohydrates: _this.carbohydrates,
                protein: _this.protein,
                video_url: _this.youtubeLink,
                ingredients: _this.ingredients,
                steps: _this.steps
            };

            console.log(data);

            if (!data.title || !data.content || !data.image) {
                alert(_this.translations['required_fields']);
                return false;
            }

            _this.loading = true;

            _this.$http.post(smrc_url + '?action=smrc_add_recipe&nonce=' + smrc_nonces['smrc_add_recipe'], data).then(function (res) {
                res = res.body;
                _this.loading = false;

                if (typeof res.error !== 'undefined') {
                    _this.$set(_this, 'error', res.error);
                }

                if (typeof res.success !== 'undefined') {
                    _this.$set(_this, 'success', res.success);
                }
            });
        },

        addStep: function addStep() {
            this.steps.push({
                'image': '',
                'content': ''
            });
        },
        deleteStep: function deleteStep(index) {
            //console.log(this.steps, index);
            this.steps.splice(index, 1);
        },
        editPost: function editPost() {

            var recipe_data = smrc_add_recipe['edit_recipe'];

            if (typeof recipe_data !== 'undefined' && _typeof(recipe_data.id)) {
                var _this = this;

                if (typeof recipe_data.post_title !== 'undefined') {
                    _this.$set(_this, 'name', recipe_data.post_title);
                }
                if (typeof recipe_data.id !== 'undefined') {
                    _this.$set(_this, 'id', recipe_data.id);
                }
                if (typeof recipe_data.image !== 'undefined') {
                    _this.$set(_this, 'image', recipe_data.image);
                }
                if (typeof recipe_data.post_content !== 'undefined') {
                    _this.$set(_this, 'content', recipe_data.post_content);
                }
                if (typeof recipe_data.metas.cooking_time !== 'undefined') {
                    _this.$set(_this, 'cookingTime', recipe_data.metas.cooking_time);
                }
                if (typeof recipe_data.metas.difficulty !== 'undefined') {
                    _this.$set(_this, 'level', recipe_data.metas.difficulty);
                }
                if (typeof recipe_data.metas.calories !== 'undefined') {
                    _this.$set(_this, 'calories', recipe_data.metas.calories);
                }
                if (typeof recipe_data.metas.fat !== 'undefined') {
                    _this.$set(_this, 'fat', recipe_data.metas.fat);
                }
                if (typeof recipe_data.metas.carbohydrates !== 'undefined') {
                    _this.$set(_this, 'carbohydrates', recipe_data.metas.carbohydrates);
                }
                if (typeof recipe_data.metas.protein !== 'undefined') {
                    _this.$set(_this, 'protein', recipe_data.metas.protein);
                }
                if (typeof recipe_data.metas.video_url !== 'undefined') {
                    _this.$set(_this, 'youtubeLink', recipe_data.metas.video_url);
                }
                if (typeof recipe_data.metas.servings !== 'undefined') {
                    _this.$set(_this, 'servings', recipe_data.metas.servings);
                }
                if (typeof recipe_data.categories !== 'undefined') {
                    _this.$set(_this, 'categories', recipe_data.categories);
                }
                if (typeof recipe_data.ingredients !== 'undefined') {
                    _this.$set(_this, 'ingredients', recipe_data.ingredients);
                }
                if (typeof recipe_data.steps !== 'undefined') {
                    _this.$set(_this, 'steps', recipe_data.steps);
                }
            }
        },
        generateKey: function generateKey() {
            return Math.random().toString(36).substr(2);
        }
    },
    mounted: function mounted() {
        var _this = this;
        _this.editPost();
        Vue.nextTick(function () {

            _this.waitForTinyMCE();
        });
    }
});