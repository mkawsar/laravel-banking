<template>
    <div class="row">
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
                <form autocomplete="off" enctype="multipart/form-data">
                    <div class="card-header">
                        <h4 class="card-title">
                            Enter Member Details Below
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="form-group required">
                            <label for="member_id" class="control-label">Member ID</label>
                            <input type="text"
                                   name="member_id"
                                   id="member_id"
                                   v-validate="memberValidations.member_id"
                                   v-model="member.member_id"
                                   placeholder="0001/0096"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('member_id')">{{
                                    errors.first('member_id')
                                }}</span>
                        </div>
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
                            <span class="text-danger"
                                  v-show="errors.has('mother_name')">{{ errors.first('mother_name') }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="present_address" class="control-label">Present Address</label>
                            <textarea class="form-control"
                                      id="present_address"
                                      name="present_address"
                                      v-model="member.present_address"
                                      v-validate="memberValidations.present_address"></textarea>
                            <span class="text-danger"
                                  v-show="errors.has('present_address')">{{ errors.first('present_address') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="same" class="control-label">Same(Present Address)</label>
                            <input type="checkbox" value="same" id="same" @click="handleIsChecked($event)"
                                   :disabled="member.present_address === null || member.present_address === ''">
                        </div>

                        <div class="form-group required">
                            <label for="permanent_address" class="control-label">Permanent Address</label>
                            <textarea class="form-control"
                                      id="permanent_address"
                                      name="permanent_address"
                                      v-validate="memberValidations.permanent_address"
                                      v-model="member.permanent_address"></textarea>
                            <span class="text-danger"
                                  v-show="errors.has('permanent_address')">{{
                                    errors.first('permanent_address')
                                }}</span>
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
                            <span class="text-danger" v-show="errors.has('nid')">{{ errors.first('nid') }}</span>
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
                            <label class="control-label">Date of Birth</label>
                            <datepicker :format="format"
                                        v-model="member.dob"
                                        v-validate="memberValidations.dob"
                                        id="bc-data-date_value"
                                        input-class="form-control"/>
                            <span class="text-danger" v-show="errors.has('dob')">{{ errors.first('dob') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nationality" class="control-label">Nationality</label>
                            <input type="text"
                                   name="nationality"
                                   id="nationality"
                                   v-model="member.nationality"
                                   placeholder="Nationality"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="education_qualification" class="control-label">Education Qualification</label>
                            <input type="text"
                                   name="education_qualification"
                                   id="education_qualification"
                                   v-model="member.education_qualification"
                                   placeholder="Education Qualification"
                                   class="form-control">
                        </div>

                        <div class="form-group required">
                            <label for="nominee_name" class="control-label">Nominee Name</label>
                            <input type="text"
                                   name="nominee_name"
                                   id="nominee_name"
                                   v-model="member.nominee_name"
                                   v-validate="memberValidations.nominee_name"
                                   placeholder="Nominee Name"
                                   class="form-control">
                            <span class="text-danger"
                                  v-show="errors.has('nominee_name')">{{ errors.first('nominee_name') }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="nominee_relation" class="control-label">Nominee Relation</label>
                            <input type="text"
                                   name="nominee_relation"
                                   id="nominee_relation"
                                   v-model="member.nominee_relation"
                                   v-validate="memberValidations.nominee_relation"
                                   placeholder="Nominee Relation"
                                   class="form-control">
                            <span class="text-danger"
                                  v-show="errors.has('nominee_relation')">{{ errors.first('nominee_relation') }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="nominee_father_name" class="control-label">Nominee Father Name</label>
                            <input type="text"
                                   name="nominee_father_name"
                                   id="nominee_father_name"
                                   v-model="member.nominee_father_name"
                                   v-validate="memberValidations.nominee_father_name"
                                   placeholder="Nominee Father Name"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('nominee_father_name')">{{
                                    errors.first('nominee_father_name')
                                }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="nominee_mother_name" class="control-label">Nominee Mother Name</label>
                            <input type="text"
                                   name="nominee_mother_name"
                                   id="nominee_mother_name"
                                   v-model="member.nominee_mother_name"
                                   v-validate="memberValidations.nominee_mother_name"
                                   placeholder="Nominee Mother Name"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('nominee_mother_name')">{{
                                    errors.first('nominee_mother_name')
                                }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="nominee_address" class="control-label">Nominee Address</label>
                            <textarea type="text"
                                      name="nominee_address"
                                      id="nominee_address"
                                      v-model="member.nominee_address"
                                      v-validate="memberValidations.nominee_address"
                                      placeholder="Nominee Address"
                                      class="form-control"></textarea>
                            <span class="text-danger"
                                  v-show="errors.has('nominee_address')">{{ errors.first('nominee_address') }}</span>
                        </div>
                        <div class="form-group required">
                            <label for="picture" class="control-label">Member Picture</label>
                            <input type="file"
                                   name="picture"
                                   id="picture"
                                   ref="picture"
                                   @change="handleFileOnChange"
                                   accept="image/*"
                                   v-validate="memberValidations.picture"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('picture')">{{
                                    errors.first('picture')
                                }}</span>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" @click.prevent="handleCreateNewUser"
                                class="btn btn-outline btn-info btn-wd">Create User
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

let veeCustomMessage = {
    en: {
        custom: {
            member_id: {
                required: 'Member ID field is required',
                numeric: 'Member ID must be numeric number',
                min: 'Member ID field must be at least 4 characters',
                max: 'Member ID field may not be greater than 4 characters'
            },
            name: {
                required: 'Full name field is required',
            },
            father_name: {
                required: 'Father name field is required',
            },
            mother_name: {
                required: 'Mother name field is required',
            },
            present_address: {
                required: 'Present address field is required',
            },
            permanent_address: {
                required: 'Permanent address field is required',
            },
            nid: {
                required: 'National ID field is required',
            },
            phone: {
                required: 'Phone number field is required',
            },
            dob: {
                required: 'Date of birth field is required',
            },
            nominee_name: {
                required: 'Nominee name field is required',
            },
            nominee_relation: {
                required: 'Nominee relation field is required',
            },
            nominee_father_name: {
                required: 'Nominee father name field is required',
            },
            nominee_mother_name: {
                required: 'Nominee mother name field is required',
            },
            nominee_address: {
                required: 'Nominee address field is required',
            },
            picture: {
                required: 'Member picture field is required',
            },
        }
    }
};

let memberObj = {
    member_id: '',
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

export default {
    name: "Create",
    components: {
        datepicker: Datepicker
    },
    data() {
        return {
            member: memberObj,
            memberValidations: {
                member_id: {
                    required: true,
                    numeric: true,
                    max: 4,
                    min: 4
                },
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
            checked: false,
            year: '',
            readonly: false
        }
    },
    methods: {
        getError(fieldName) {
            return this.errors.first(fieldName)
        },

        cancel() {
            this.$router.push({name: 'MemberList'});
        },
        reset() {
            this.member.member_id = '';
            this.member.name = '';
            this.member.father_name = '';
            this.member.mother_name = '';
            this.member.present_address = '';
            this.member.permanent_address = '';
            this.member.nid = '';
            this.member.phone = '';
            this.member.nationality = '';
            this.member.dob = '';
            this.member.education_qualification = '';
            this.member.nominee_name = '';
            this.member.nominee_relation = '';
            this.member.nominee_father_name = '';
            this.member.nominee_mother_name = '';
            this.member.nominee_address = '';
            this.member.picture = '';
        },

        handleCreateNewUser() {
            this.disabled = true;
            this.$validator.validateAll().then(isValid => {
                if (isValid) {
                    let formData = new FormData();
                    formData.append('member_id', this.member.member_id);
                    formData.append('name', this.member.name);
                    formData.append('father_name', this.member.father_name);
                    formData.append('mother_name', this.member.mother_name);
                    formData.append('present_address', this.member.present_address);
                    formData.append('permanent_address', this.member.permanent_address);
                    formData.append('nid', this.member.nid);
                    formData.append('phone', this.member.phone);
                    formData.append('nationality', this.member.nationality);
                    formData.append('dob', this.$moment(this.member.dob).format('DD/MM/YYYY'));
                    formData.append('education_qualification', this.member.education_qualification);
                    formData.append('nominee_name', this.member.nominee_name);
                    formData.append('nominee_relation', this.member.nominee_relation);
                    formData.append('nominee_father_name', this.member.nominee_father_name);
                    formData.append('nominee_mother_name', this.member.nominee_mother_name);
                    formData.append('nominee_address', this.member.nominee_address);
                    formData.append('picture', this.member.picture);

                    axios.post(this.$env.BACKEND_API + 'admin/member/create', formData)
                        .then(res => {
                            if (res.data.status === 'validation' || res.data.status === 'failed') {
                                console.log(res.data)
                                this.$notification.error(this, 'Error', res.data.message);
                            } else if (res.data.status === 'success') {
                                console.log('test')
                                this.$notification.notify(this, 'Success', res.data.message);
                                this.$router.push({name: 'MemberList'});
                                this.reset();
                            } else {
                                this.$notification.error(this, 'Error', 'Somethings went wrong');
                            }
                        })
                        .catch(err => {
                            this.$notification.notifyError(this, err.response.data);
                            this.disabled = false;
                        })
                }
            });
        },
        handleIsChecked(e) {
            var $this = this;
            if (e.target.checked === true) {
                $this.member.permanent_address = $this.member.present_address;
            }
            $this.readonly = false;
        },
        handleFileOnChange() {
            this.member.picture = this.$refs.picture.files[0];
        }
    },
    mounted() {
        this.year = this.$moment().year();
    }
}
</script>

<style scoped="scoped">
</style>
