Vue.component('smrc-upload-image', {

    props: ['translations', 'uniq', 'loaded_image'],
    data: function () {
        return {
            image: {
                id: '',
                url: '',
                thumbnail: ''
            },
            file: '',
            loading: '',
            error: ''
        }
    },
    mounted: function () {
        let _this = this;
        if (typeof _this.loaded_image !== 'undefined' && typeof _this.loaded_image.id !== 'undefined') {
            _this.$set(_this, 'image', _this.loaded_image);
        }
    },
    methods: {
        uploadImage: function (event) {
            let _this = this;
            if (typeof event.target.files[0] !== 'undefined') {

                let data = new FormData();
                data.append('file', event.target.files[0]);

                /*Upload*/
                _this.loading = true;
                _this.$http.post(`${smrc_url}?action=smrc_upload_image&nonce=${smrc_nonces['smrc_upload_image']}`, data).then(function (res) {
                    res = res.body;
                    _this.loading = false;
                    if (typeof res.error !== 'undefined') {
                        _this.$set(_this, 'error', res.error)
                    }

                    if (typeof res.id !== 'undefined') {
                        _this.$set(_this.image, 'id', res.id);
                        _this.$set(_this.image, 'url', res.url);
                        _this.$set(_this.image, 'thumbnail', res.thumbnail);
                    }
                });
            }
        },
        deleteImage() {
            let _this = this;
            if (!confirm(_this.translations['delete_image'])) return false;

            _this.$set(_this, 'image', {
                id: '',
                url: '',
                thumbnail: '',
            });

        }
    },
    watch: {
        image: {
            deep: true,
            handler: function () {
                let _this = this;
                _this.$emit('get-image', _this.image)
            }
        }
    },
    template: `
        <div class="smrc_add_recipe__image">
            
            <div class="smrc_add_recipe__image_uploaded" v-if="image.id">
                <img :src="image.thumbnail" />
                <i class="lnricons-cross" @click="deleteImage"></i>
            </div>
            
            <div class="smrc_add_recipe__image_load" v-else>
            
                <div class="smrc_add_recipe__image_upload" v-if="!loading">    
                    <input type="file" accept="image/*" @change="uploadImage($event)" :id="uniq">
                </div>
                
                <div class="smrc_add_recipe__image_upload" v-else v-html="translations.loading_image"></div>
                
            </div>
        </div>
    `,
});