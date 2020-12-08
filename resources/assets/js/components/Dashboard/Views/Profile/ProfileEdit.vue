<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form>
                    <div class="card-header">
                        <h4 class="card-title">
                            Update Profile Info
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="form-group required">
                            <label for="name" class="control-label">Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control"
                                   v-validate="userValidations.name"
                                   v-model="user.name">
                            <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="email" class="control-label">Email</label>
                            <input type="email"
                                   disabled
                                   name="email"
                                   id="email"
                                   class="form-control"
                                   v-validate="userValidations.email"
                                   v-model="user.email">
                            <span class="text-danger"
                                  v-show="errors.has('email')">{{ errors.first('email') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="role" class="control-label">Permission Role</label>
                            <input type="text"
                                   id="role"
                                   name="role"
                                   class="form-control"
                                   v-model="user.role.name"
                                   disabled>

                        </div>
                        <div class="form-group required">
                            <label for="phone" class="control-label">Phone</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control"
                                   v-validate="userValidations.phone"
                                   v-model="user.phone">
                            <span class="text-danger"
                                  v-show="errors.has('phone')">{{ errors.first('phone') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="picture" class="control-label">Profile Picture</label>
                            <input type="file"
                                   name="picture"
                                   id="picture"
                                   ref="file"
                                   class="form-control"
                                   v-on:change="handelOnFileChange">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" @click.prevent="updateUserData"
                                class="btn btn-fill btn-default btn-wd">
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
    import EventBus from "../../../../EventBus";

    let veeCustomMessage = {
        en: {
            custom: {
                name: {
                    required: 'Name field is required',
                },
                email: {
                    required: 'Email Not found',
                }
            }
        }
    };
    let userObj = {
        name: '',
        email: '',
        phone: '',
        role: '',
        picture: ''
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
            ...mapFields(['name', 'email', 'phone'])
        },
        data: function () {
            return {
                editData: {},
                roles: [],
                user: userObj,
                userValidations: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    phone: {
                        required: true,
                    },
                }
            }
        },
        methods: {
            getUserDetail() {
                axios.get(this.$env.BACKEND_API + 'auth/profile')
                    .then((response) => {
                        this.user = response.data.user;
                    })
                    .catch((error) => {
                        console.log(error.data);
                    });
            },
            getError: function (fieldName) {
                return this.errors.first(fieldName)
            },
            updateUserData: function () {
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        let formData = new FormData();
                        formData.append('picture', this.user.picture);
                        formData.append('user', JSON.stringify(this.user));
                        axios.post(this.$env.BACKEND_API + 'auth/profile/update', formData)
                            .then((res) => {
                                if (res.data.status === 'validation' || res.data.status === 'failed') {
                                    this.$notification.error(this, 'Error', res.data.message);
                                } else if (res.data.status === 'success') {
                                    this.handleFormReset();
                                    this.$notification.notify(this, 'Success', res.data.message);
                                    this.getUserDetail();
                                    EventBus.$emit('UPDATE_AUTH_USER_PROFILE');
                                } else {
                                    this.$notification.error(this, 'Error', 'Somethings went wrong');
                                }
                            })
                            .catch((error) => {
                                this.$notification.notifyError(this, error.response.data);
                            });
                    }
                });
            },
            handelOnFileChange: function () {
                this.user.picture = this.$refs.file.files[0];
            },

            handleFormReset() {
                this.user.name = '';
                this.user.email = '';
                this.user.phone = '';
                this.user.role = '';
                this.user.picture = '';
            }
        },
        mounted: function () {
            this.getUserDetail();
        }
    }
</script>
<style>
</style>
