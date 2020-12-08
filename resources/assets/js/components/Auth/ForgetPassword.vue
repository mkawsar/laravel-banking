<template>
    <div>
        <div class="wrapper wrapper-full-page">
            <div class="full-page" data-color="azure"
                 data-image="/storage/static/img/background/background-2.jpg">
                <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
                <div class="content">
                    <div class="container">
                        <div class="row text-center title-login-div">
                            <router-link class="title-login" to="/login">AMAR DIET</router-link>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                                <form method="post" action="javascript:void(0)">
                                    <div class="card" data-background="color" data-color="blue">
                                        <div class="card-header">
                                            <h3 class="card-title">Forget Password</h3>
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
                                        </div>
                                        <div class="card-footer text-center">
                                            <button class="btn btn-fill btn-wd"
                                                    @click.prevent="handleSubmitForgotPassword"
                                                    v-if="!loader">
                                                Let's go
                                            </button>
                                            <button class="btn btn-fill btn-wd"
                                                    v-else>
                                                Let's go <i class="fa fa-spinner fa-spin" v-if="loader"></i>
                                            </button>
                                            <div class="forgot">
                                                <router-link
                                                        :to="{name: 'login'}">
                                                    Back To Login?
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

                <div class="full-page-background"
                     style="background-image: url(storage/static/img/background/background-2.jpg)">

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
                }
            }
        }
    };

    let userObj = {
        email: ''
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
                    }
                },
                loader: false,
            }
        },

        methods: {
            handleSubmitForgotPassword: function () {
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        this.loader = true;
                        axios.post(this.$env.BACKEND_API + 'forgot/password/send', this.user)
                            .then(res => {
                                if (res.data.status === 'validation' || res.data.status === 'failed') {
                                    this.$notification.error(this, 'Error', res.data.message);
                                    this.loader = false;
                                } else if (res.data.status === 'success') {
                                    this.$notification.notify(this, 'Success', res.data.message);
                                    this.redirectToHome();
                                } else {
                                    this.$notification.error(this, 'Error', 'Somethings went wrong');
                                }
                            })
                            .catch(err => {
                                console.log(err.response);
                            })
                    }
                })
            },

            redirectToHome: function () {
                this.$router.push('/login');
                this.reset();
            },
            reset: function () {
                this.user.email = '';
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
