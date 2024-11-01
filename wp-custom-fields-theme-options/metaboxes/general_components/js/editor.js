Vue.component('vue-editor', Vue2Editor.default.VueEditor);
Vue.component('wpcfto_editor', {
    props: ['fields', 'field_label', 'field_name', 'field_id', 'field_value'],
    data: function () {
        return {
            value: '',
        }
    },
    template: `
        <div class="wpcfto_generic_field">
            <div class="stm-lms-editor">
                <label v-html="field_label"></label>
                
                <vue-editor :id="'editor' + field_name" v-model="value"></vue-editor>
                
            </div>
        </div>
    `,
    mounted: function () {

        if (typeof this.field_value !== 'undefined') {
            this.$set(this, 'value', this.field_value);
        }

    },
    methods: {},
    watch: {
        value: function (value) {
            this.$emit('wpcfto-get-value', value);
        }
    }
});