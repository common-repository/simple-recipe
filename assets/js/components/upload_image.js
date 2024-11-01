'use strict';

Vue.component('smrc-upload-image', {

    props: ['translations', 'uniq', 'loaded_image'],
    data: function data() {
        return {
            image: {
                id: '',
                url: '',
                thumbnail: ''
            },
            file: '',
            loading: '',
            error: ''
        };
    },
    mounted: function mounted() {
        var _this = this;
        if (typeof _this.loaded_image !== 'undefined' && typeof _this.loaded_image.id !== 'undefined') {
            _this.$set(_this, 'image', _this.loaded_image);
        }
    },
    methods: {
        uploadImage: function uploadImage(event) {
            var _this = this;
            if (typeof event.target.files[0] !== 'undefined') {

                var data = new FormData();
                data.append('file', event.target.files[0]);

                /*Upload*/
                _this.loading = true;
                _this.$http.post(smrc_url + '?action=smrc_upload_image&nonce=' + smrc_nonces['smrc_upload_image'], data).then(function (res) {
                    res = res.body;
                    _this.loading = false;
                    if (typeof res.error !== 'undefined') {
                        _this.$set(_this, 'error', res.error);
                    }

                    if (typeof res.id !== 'undefined') {
                        _this.$set(_this.image, 'id', res.id);
                        _this.$set(_this.image, 'url', res.url);
                        _this.$set(_this.image, 'thumbnail', res.thumbnail);
                    }
                });
            }
        },
        deleteImage: function deleteImage() {
            var _this = this;
            if (!confirm(_this.translations['delete_image'])) return false;

            _this.$set(_this, 'image', {
                id: '',
                url: '',
                thumbnail: ''
            });
        }
    },
    watch: {
        image: {
            deep: true,
            handler: function handler() {
                var _this = this;
                _this.$emit('get-image', _this.image);
            }
        }
    },
    template: '\n        <div class="smrc_add_recipe__image">\n            \n            <div class="smrc_add_recipe__image_uploaded" v-if="image.id">\n                <img :src="image.thumbnail" />\n                <i class="lnricons-cross" @click="deleteImage"></i>\n            </div>\n            \n            <div class="smrc_add_recipe__image_load" v-else>\n            \n                <div class="smrc_add_recipe__image_upload" v-if="!loading">    \n                    <input type="file" accept="image/*" @change="uploadImage($event)" :id="uniq">\n                </div>\n                \n                <div class="smrc_add_recipe__image_upload" v-else v-html="translations.loading_image"></div>\n                \n            </div>\n        </div>\n    '
});