<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form>
                    <div class="card-header">
                        <h4 class="card-title">
                            Change Password
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="form-group">
                            <label for="password" class="control-label">Current Password</label>
                            <input type="password"
                                   name="current"
                                   id="current"
                                   class="form-control"
                                   v-validate="userValidations.current"
                                   v-model="user.current_password">
                            <span class="text-danger"
                                  v-show="errors.has('current')">{{ errors.first('current') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   ref="password"
                                   class="form-control"
                                   v-validate="userValidations.password"
                                   v-model="user.password">
                            <span class="text-danger"
                                  v-show="errors.has('password')">{{ errors.first('password') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="confirm" class="control-label">Confirm Password</label>
                            <input type="password"
                                   name="confirm"
                                   id="confirm"
                                   class="form-control"
                                   v-validate="userValidations.confirmPassword"
                                   v-model="user.confirm_password">
                            <span class="text-danger"
                                  v-show="errors.has('confirm')">{{ errors.first('confirm') }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" @click.prevent="updateUserData" class="btn btn-fill btn-info btn-wd">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import VeeValidate from 'vee-validate'
    import {mapFields} from 'vee-validate'

    let veeCustomMessage = {
        en: {
            custom: {
                current: {
                    min: 'Password should contain at least 6 character',
                }, password: {
                    min: 'Password should contain at least 6 character',
                },
                confirm: {
                    confirmed: 'The confirm password field must be same as password'
                },
            }
        }
    };

    let userObj = {
        current_password: '',
        password: '',
        confirm_password: '',
    };

    Vue.use(VeeValidate, {
        dictionary: veeCustomMessage,
        fieldsBagName: userObj
    });

    Vue.use(VeeValidate, {
        dictionary: veeCustomMessage
    });

    export default {
        computed: {
            ...mapFields(['current', 'password', 'confirm',])
        },
        data: function () {
            return {
                editData: {},
                user: userObj,
                userValidations: {
                    current: {
                        min: 6
                    }, password: {
                        min: 6
                    },
                    confirmPassword: {
                        confirmed: 'password'
                    },
                }
            }
        },

        methods: {
            getError: function (fieldName) {
                return this.errors.first(fieldName)
            },

            updateUserData: function () {
                var self = this;
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        axios.post(this.$env.BACKEND_API + 'auth/change/password', this.user)
                            .then(res => {
                                if (res.data.status === 'validation') {
                                    this.$notification.error(this, 'Error', res.data.message);
                                } else if (res.data.status === 'success') {
                                    this.$notification.notify(this, 'success', res.data.message);
                                    this.$localStorage.clear();
                                    this.$router.push({name: 'login'})
                                }
                            })
                            .catch(err => {
                                console.log(err)
                            })
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
