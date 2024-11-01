'use strict';

Vue.component('smrc-login-register', {

    /**
     * @var smrc_login_data
     */

    props: ['translations', 'warning'],
    data: function data() {
        return {
            login: '',
            password: '',

            loggedIn: smrc_login_data['logged_in'],
            userData: smrc_login_data['user_data'],
            error: '',
            message: '',

            isLoginFrame: true,

            name: '',
            last_name: '',
            email: '',
            re_password: ''
        };
    },
    methods: {
        logIn: function logIn() {
            var _this = this;

            _this.clearError();

            if (!_this.login || !_this.password) {
                _this.fieldsError();
                return false;
            }

            var data = {
                login: _this.login,
                password: _this.password
            };

            _this.$http.post(smrc_url + '?action=smrc_login', data, { emulateJSON: true }).then(function (r) {
                var data = r.body;
                _this.insertUser(data);
            });
        },
        switchLogin: function switchLogin() {
            this.isLoginFrame = !this.isLoginFrame;
        },
        clearError: function clearError() {
            var _this = this;
            _this.error = _this.message = '';
        },
        fieldsError: function fieldsError() {
            var _this = this;
            _this.error = true;
            _this.message = _this.translations.required_fields;
        },

        register: function register() {
            var _this = this;

            _this.clearError();

            if (!_this.login || !_this.password || !_this.email || !_this.re_password || !_this.last_name || !_this.name) {
                _this.fieldsError();
                return false;
            }

            var data = {
                login: _this.login,
                password: _this.password,
                email: _this.email,
                re_password: _this.re_password,
                last_name: _this.last_name,
                name: _this.name
            };

            _this.$http.post(smrc_url + '?action=smrc_register', data, { emulateJSON: true }).then(function (r) {
                var data = r.body;
                _this.insertUser(data);
            });
        },
        insertUser: function insertUser(data) {
            var _this = this;
            if (typeof data.error !== 'undefined') _this.$set(_this, 'error', data.error);
            if (typeof data.logged_in !== 'undefined') _this.$set(_this, 'loggedIn', data.logged_in);
            if (typeof data.message !== 'undefined') _this.$set(_this, 'message', data.message);
            if (typeof data.user_data !== 'undefined') _this.$set(_this, 'userData', data.user_data);
        }
    },
    computed: {
        userLogin: function userLogin() {
            var _this = this;
            var data = _this.userData.data;
            var displayName = data.display_name !== 'undefined' ? data.display_name : data.user_login;
            displayName = displayName + ' (' + data.user_email + ')';

            return displayName;
        }
    },
    watch: {
        loggedIn: function loggedIn() {
            var _this = this;
            _this.$emit('login-data', _this.loggedIn);
        }
    },
    template: '\n<div class="smrc_login_register">\n\n    <div class="smrc_login_register__login" v-if="!loggedIn">\n    \n       <blockquote v-html="warning" v-if="warning"></blockquote>\n    \n        <!--LOGIN-->\n        <div class="smrc_login_register__login_login" v-if="isLoginFrame">\n    \n            <div class="smrc_row">\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="text" :placeholder="translations.enter_login" v-model="login" />\n                </div>\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="password" :placeholder="translations.enter_password" v-model="password" />\n                </div>\n            </div>\n            \n            <div class="smrc_row">\n                <div class="smrc_col-md-6">\n                    <div class="smrc_login_register__login_submit" @click="logIn()">\n                        <span v-html="translations.submit_login" class="btn"></span>\n                    </div>\n                </div>\n                <div class="smrc_col-md-6">\n                    <div class="smrc_login_register__login_switch" @click="switchLogin()">\n                        <span v-html="translations.registration" class="btn btn-simple"></span>\n                    </div>\n                </div>\n            </div>\n            \n        </div>\n        \n        <!--Register-->\n        <div class="smrc_login_register__login_register" v-else>\n            \n            <div class="smrc_row ">\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="text" :placeholder="translations.enter_login" v-model="login" />\n                </div>\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="text" :placeholder="translations.enter_email" v-model="email" />\n                </div>\n            </div>\n            \n            <div class="smrc_row">\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="text" :placeholder="translations.enter_name" v-model="name" />\n                </div>\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="text" :placeholder="translations.enter_last_name" v-model="last_name" />\n                </div>\n            </div>\n            \n            <div class="smrc_row">\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="password" :placeholder="translations.enter_password" v-model="password" />\n                </div>\n                <div class="smrc_col-md-6 smrc_mgb_30">\n                    <input type="password" :placeholder="translations.enter_re_password" v-model="re_password" />\n                </div>\n            </div>\n            \n            \n            <div class="smrc_row">\n                <div class="smrc_col-md-6">\n                    <div class="smrc_login_register__login_submit" @click="register()">\n                        <span v-html="translations.submit_register" class="btn"></span>\n                    </div>\n                </div>\n                <div class="smrc_col-md-6">\n                    <div class="smrc_login_register__login_switch" @click="switchLogin()">\n                        <span v-html="translations.cancel" class="btn btn-simple"></span>\n                    </div>\n                </div>\n            </div>\n            \n            \n        </div>\n        \n        <!--Login Error-->\n        <div class="smrc_message" v-bind:class="\'smrc_message_\' + error" v-if="error && message" v-html="message"></div>\n        \n       \n   </div> \n   \n    \n   <div class="smrc_login_register__data" v-else>\n        {{translations[\'logged_in\']}} {{userLogin}}\n    </div>\n    \n</div>\n    '
});