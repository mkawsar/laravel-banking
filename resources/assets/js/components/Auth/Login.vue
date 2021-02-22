<template>
    <div>
        <div class="wrapper wrapper-full-page">
            <div class="full-page" data-color="azure"
                 data-image="storage/static/img/background/background-1.jpg">
                <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
                <div class="content">
                    <div class="container">
                        <div class="row text-center title-login-div">
                            <router-link class="title-login" to="/login">MSRSSL</router-link>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                                <form method="post" action="javascript:void(0)">
                                    <div class="card" data-background="color" data-color="blue">
                                        <div class="card-header">
                                            <h3 class="card-title">Login</h3>
                                        </div>
                                        <div class="card-content">
                                            <div class="form-group">
                                                <label>Email address</label>
                                                <input type="email" placeholder="Enter email"
                                                       class="form-control input-no-border"
                                                       v-validate="userValidations.email"
                                                       name="email"
                                                       v-model="user.email">
                                                <span class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" placeholder="Password"
                                                       class="form-control input-no-border"
                                                       v-validate="userValidations.password"
                                                       name="password"
                                                       v-model="user.password">
                                                <span class="text-danger" v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <button class="btn btn-fill btn-wd"
                                                    @click.prevent="login"
                                                    v-if="!loader">
                                                Let's go
                                            </button>
                                            <button class="btn btn-fill btn-wd"
                                                    v-else>
                                                Let's go <i class="fa fa-spinner fa-spin" v-if="loader"></i>
                                            </button>
                                            <div class="forgot">
                                                <router-link
                                                        :to="{name: 'forget-password'}">
                                                    Forgot your password?
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <app-footer></app-footer>

                <div class="full-page-background bg-image"
                     style="background-image: url(storage/static/img/background/background-1.jpg)">

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import VeeValidate from 'vee-validate'
    import {mapFields, Validator} from 'vee-validate'
    import Footer from '~/components/GeneralsComponents/Footer';

    let veeCustomMessage = {
        en: {
            custom: {
                email: {
                    required: 'Email field is required',
                    email: '',
                },
                password: {
                    required: 'Password field is required',
                }
            }
        }
    };

    let userObj = {
        email: '',
        password: ''
    };

    Vue.use(VeeValidate, {
        dictionary: veeCustomMessage,
        fieldsBagName: userObj
    });

    export default {
        components: {
            appFooter: Footer
        },

        data: function () {
            return {
                user: userObj,
                userValidations: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        min: 6
                    }
                },
                loader: false,
            }
        },

        methods: {
            login: function () {
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        this.loader = true;
                        axios.post(this.$env.BACKEND_API + 'auth/login', this.user)
                            .then((response) => {
                                Object.keys(response.data).forEach((key) => {
                                    this.$localStorage.set(key, response.data[key]);
                                    if (key === 'user') {
                                        let userRoles = [];
                                        let roles = response.data[key].role.name;
                                        userRoles.push(roles);
                                        this.$localStorage.set('roles', userRoles);
                                    }
                                });
                                this.redirectToHome();
                                this.loader = false;
                            })
                            .catch((error) => {
                                this.$notification.notifyError(this, error.response.data);
                                this.loader = false;
                            })
                    }
                })
            },

            redirectToHome: function () {
                this.$router.push('/admin');
                this.reset();
            },
            reset: function () {
                this.user.email = '';
                this.user.password = '';
            }
        },

        mounted: function () {
            this.$localStorage.clear();
        }
    }
</script>

<style scoped>
    .title-login-div {
        margin: 25px 0;
        font-size: 25px;
        color: #fff !important;
        font-weight: 600;
    }

    .title-login {
        color: #fff;
    }

    .forgot {
        margin-top: 10px;
    }
</style>
