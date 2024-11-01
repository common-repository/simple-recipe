(function ($) {


    let smrcTimeout;

    $(document).ready(function () {
        $('.smrc_filter_field__autocomplete').each(function () {
            let $this = $(this);

            let type = $this.attr('data-type');

            new Vue({
                el: this,
                data: {
                    s: '',
                    type: type,
                    choices: [],
                    loading: false,
                    tags: [],
                    value: ''
                },
                mounted: function () {
                    this.$set(this, 'tags', window[`current_${type}`]);
                },
                methods: {
                    searchTerms() {
                        let _this = this;

                        clearTimeout(smrcTimeout);

                        smrcTimeout = setTimeout(function () {
                            _this.loading = true;
                            if (_this.s !== '') {
                                _this.$http.get(`${smrc_url}?action=smrc_get_terms&taxonomy=${_this.type}&terms=${_this.s}&exclude=${_this.value}`)
                                    .then(function (r) {
                                        r = r.body;
                                        _this.loading = false;
                                        _this.$set(_this, 'choices', r);
                                    });
                            }
                        }, 500);
                    },
                    selectTerm(term) {
                        let _this = this;
                        _this.tags.push(term);
                        _this.$set(_this, 'choices', []);
                        _this.s = '';
                    }
                },
                watch: {
                    tags: {
                        handler: function (terms) {
                            let values = [];
                            terms.forEach(function (term) {
                                values.push(term.id);
                            });
                            this.$set(this, 'value', values.join(','));
                        },
                        deep: true
                    }
                }
            })


        });
    });
})(jQuery);