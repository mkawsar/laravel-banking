<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">Home</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'UserList'}">User List</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">List</li>
            </ol>
        </nav>
        <div class="col-md-12">
            <div class="card">
                <form>
                    <div class="card-header">
                        <h4 class="card-title">
                            Enter User Details Below
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="form-group required">
                            <label for="name" class="control-label">Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   v-validate="userValidations.name"
                                   v-model="user.name"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                        </div>

                        <div class="form-group required">
                            <label for="email" class="control-label">Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control"
                                   v-validate="userValidations.email"
                                   v-model="user.email">
                            <span class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</span>
                        </div>

                        <div class="form-group required">
                            <label for="role_id" class="control-label">Roles</label>
                            <select name="role_id" id="role_id" class="form-control" v-model="user.role_id"
                                    v-validate="userValidations.role_id">
                                <option v-for="(role, index) in roles" :key="index" v-bind:value="role.id">{{
                                        role.name
                                    }} ({{ role.description }})
                                </option>
                            </select>
                            <span class="text-danger"
                                  v-show="errors.has('role_id')">{{ errors.first('role_id') }}</span>
                        </div>
                        <div class="form-group required">
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
                        <div class="form-group required">
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
                        <button type="submit" @click.prevent="handleCreateNewUser"
                                class="btn btn-outline btn-info btn-wd"
                                v-bind:disabled="disabled">Create User
                        </button>
                        <button type="submit" @click.prevent="cancel"
                                class="btn btn-outline btn-danger btn-wd"
                                style="margin-left: 15px">Cancel
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
import {mapFields, Validator} from 'vee-validate'

let veeCustomMessage = {
    en: {
        custom: {
            name: {
                required: 'Name field is required',
            },
            email: {
                required: 'Email field is required',
                email: '',
            },
            role_id: {
                required: 'Please select a Role',
            },
            password: {
                required: 'Password field is required',
            },
            confirm: {
                required: 'Confirm password field is required',
                confirmed: 'The confirm password field must be same as password'
            }
        }
    }
};

let userObj = {
    name: '',
    email: '',
    role_id: '',
    password: '',
    confirm_password: '',
};

Vue.use(VeeValidate, {
    dictionary: veeCustomMessage,
    fieldsBagName: userObj
});
export default {
    name: "Create",
    data() {
        return {
            user: userObj,
            userValidations: {
                name: {
                    required: true
                },
                role_id: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    min: 6
                },
                confirmPassword: {
                    required: true,
                    confirmed: 'password'
                }
            },
            roles: [],
            disabled: false
        }
    },
    methods: {
        getError(fieldName) {
            return this.errors.first(fieldName)
        },
        handleGetAllRole() {
            this.roles = [];
            axios.get(this.$env.BACKEND_API + 'admin/all/role')
                .then(res => {
                    if (res.data) {
                        this.roles = res.data;
                    }
                })
                .catch(err => {
                    console.log(err)
                });
        },
        cancel: function () {
            this.$router.push({name: 'UserList'});
        },
        reset: function () {
            this.user = {};
        },

        handleCreateNewUser() {
            this.disabled = true;
            this.$validator.validateAll().then(isValid => {
                if (isValid) {
                    let formData = new FormData();
                    formData.append('name', this.user.name);
                    formData.append('email', this.user.email);
                    formData.append('password', this.user.password);
                    formData.append('confirm_password', this.user.confirm_password);
                    formData.append('role_id', this.user.role_id);

                    axios.post(this.$env.BACKEND_API + 'admin/user/create', formData)
                        .then(res => {
                            if (res.data.status === 'validation' || res.data.status === 'failed') {
                                this.$notification.error(this, 'Error', res.data.message);
                            } else if (res.data.status === 'success') {
                                this.$notification.notify(this, 'Success', res.data.message);
                                this.$router.push({name: 'UserList'});
                                this.reset();
                            } else {
                                this.$notification.error(this, 'Error', 'Somethings went wrong');
                            }
                            this.disabled = false;
                        })
                        .catch(err => {
                            this.$notification.notifyError(this, err.response.data);
                            this.disabled = false;
                        })
                }
            });
        }
    },
    mounted() {
        this.handleGetAllRole();
    }
}
</script>

<style scoped>

</style>
