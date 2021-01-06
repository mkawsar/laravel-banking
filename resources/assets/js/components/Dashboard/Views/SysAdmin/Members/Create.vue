<template>
    <div class="row">
        <pre>{{ member }}</pre>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">Home</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'MemberList'}">Member List</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>
        <div class="col-md-12">
            <div class="card">
                <form autocomplete="off">
                    <div class="card-header">
                        <h4 class="card-title">
                            Enter Member Details Below
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="form-group required">
                            <label for="name" class="control-label">Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   v-validate="memberValidations.name"
                                   v-model="member.name"
                                   placeholder="Full Name"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                        </div>

                        <div class="form-group required">
                            <label for="name" class="control-label">Father Name</label>
                            <input type="text"
                                   name="father_name"
                                   id="father_name"
                                   v-validate="memberValidations.father_name"
                                   v-model="member.father_name"
                                   placeholder="Father Name"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('father_name')">{{
                                    errors.first('father_name')
                                }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="mother_name" class="control-label">Father Name</label>
                            <input type="text"
                                   name="mother_name"
                                   id="mother_name"
                                   v-validate="memberValidations.mother_name"
                                   v-model="member.mother_name"
                                   placeholder="Mother Name"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('mother_name')">{{errors.first('mother_name')}}</span>
                        </div>
                        <div class="form-group required">
                            <label for="present_address" class="control-label">Present Address</label>
                            <textarea class="form-control"
                                      id="present_address"
                                      name="present_address"
                                      v-model="member.present_address"
                                      v-validate="memberValidations.present_address"></textarea>
                            <span class="text-danger" v-show="errors.has('present_address')">{{errors.first('present_address') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="same" class="control-label">Same(Present Address)</label>
                            <input type="checkbox" value="same" id="same">
                        </div>

                        <div class="form-group required">
                            <label for="permanent_address" class="control-label">Permanent Address</label>
                            <textarea class="form-control"
                                      id="permanent_address"
                                      name="permanent_address"
                                      v-model="member.permanent_address"
                                      v-validate="memberValidations.permanent_address"></textarea>
                            <span class="text-danger" v-show="errors.has('permanent_address')">{{errors.first('permanent_address') }}</span>
                        </div>

                        <div class="form-group required">
                            <label for="nid" class="control-label">National ID Card</label>
                            <input type="text"
                                   name="nid"
                                   id="nid"
                                   v-validate="memberValidations.nid"
                                   v-model="member.nid"
                                   placeholder="NID"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('nid')">{{errors.first('nid') }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="phone" class="control-label">Mobile Number</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   v-validate="memberValidations.phone"
                                   v-model="member.phone"
                                   placeholder="Mobile Number"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('phone')">{{ errors.first('phone') }}</span>
                        </div>

                        <div class="form-group required">
                            <label for="dob" class="control-label">Date of Birth</label>
                            <datepicker :format="format"
                                        v-model="member.dob"
                                        id="bc-data-date_value" input-class="form-control"/>
                            <span class="text-danger" v-show="errors.has('dob')">{{errors.first('dob') }}</span>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" @click.prevent=""
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
import Datepicker from "vuejs-datepicker/dist/vuejs-datepicker.esm.js";
import * as lang from "vuejs-datepicker/src/locale";

// import datePicker from 'vue-bootstrap-datetimepicker';
//
// // Import date picker css
// import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';

let veeCustomMessage = {
    en: {
        custom: {
            name: {
                required: 'Full name field is required',
            },
            father_name: {
                required: 'Father name field is required',
            },
        }
    }
};

let memberObj = {
    name: '',
    father_name: '',
    mother_name: '',
    present_address: '',
    permanent_address: '',
    nid: '',
    phone: '',
    nationality: '',
    dob: '',
    education_qualification: '',
    nominee_name: '',
    nominee_relation: '',
    nominee_father_name: '',
    nominee_mother_name: '',
    nominee_address: '',
    picture: ''
};

Vue.use(VeeValidate, {
    dictionary: veeCustomMessage,
    fieldsBagName: memberObj
});

// Vue.component('date-picker', datePicker);

export default {
    name: "Create",
    components: {
        datepicker: Datepicker
    },
    data() {
        return {
            member: memberObj,
            memberValidations: {
                name: {
                    required: true
                },
                father_name: {
                    required: true
                },
                mother_name: {
                    required: true
                },
                present_address: {
                    required: true
                },
                permanent_address: {
                    required: true
                },
                nid: {
                    required: true
                },
                phone: {
                    required: true
                },
                nominee_name: {
                    required: true
                },
                nominee_relation: {
                    required: true
                },
                nominee_father_name: {
                    required: true
                },
                nominee_address: {
                    required: true
                },
                picture: {
                    required: true
                },

            },
            disabled: false,
            format: "dd/MM/yyyy",
            // options: {
            //     format: 'DD/MM/YYYY',
            //     useCurrent: false,
            //     showClear: true,
            //     showClose: true,
            // }
        }
    },
    methods: {
        getError(fieldName) {
            return this.errors.first(fieldName)
        },

        cancel: function () {
            this.$router.push({name: 'MemberList'});
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

    }
}
</script>

<style scoped="scoped">
.example {
    background: #f2f2f2;
    border: 1px solid #ddd;
    padding: 0em 1em 1em;
    margin-bottom: 2em;
}

.vdp-datepicker {
    position: relative;
    text-align: left;
}

#bc-data-date_value {
    background-color: #F3F2EE !important;
    border: 1px solid #e8e7e3 !important;
    border-radius: 4px !important;
    color: #66615b !important;
    font-size: 14px !important;
    padding: 7px 18px !important;
    height: 40px !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}
</style>
