'use strict';

(function ($) {

    var smrcTimeout = void 0;

    $(document).ready(function () {
        $('.smrc_filter_field__autocomplete').each(function () {
            var $this = $(this);

            var type = $this.attr('data-type');

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
                mounted: function mounted() {
                    this.$set(this, 'tags', window['current_' + type]);
                },
                methods: {
                    searchTerms: function searchTerms() {
                        var _this = this;

                        clearTimeout(smrcTimeout);

                        smrcTimeout = setTimeout(function () {
                            _this.loading = true;
                            if (_this.s !== '') {
                                _this.$http.get(smrc_url + '?action=smrc_get_terms&taxonomy=' + _this.type + '&terms=' + _this.s + '&exclude=' + _this.value).then(function (r) {
                                    r = r.body;
                                    _this.loading = false;
                                    _this.$set(_this, 'choices', r);
                                });
                            }
                        }, 500);
                    },
                    selectTerm: function selectTerm(term) {
                        var _this = this;
                        _this.tags.push(term);
                        _this.$set(_this, 'choices', []);
                        _this.s = '';
                    }
                },
                watch: {
                    tags: {
                        handler: function handler(terms) {
                            var values = [];
                            terms.forEach(function (term) {
                                values.push(term.id);
                            });
                            this.$set(this, 'value', values.join(','));
                        },
                        deep: true
                    }
                }
            });
        });
    });
})(jQuery);