<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">হোম</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'TotalSavingsList'}">মোট সঞ্চয় লিস্ট</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">সঞ্চয় উত্তোলন</li>
            </ol>
        </nav>
        <div class="col-md-12">
            <div class="card">
                <form autocomplete="off" @submit.prevent="handleSavingsAmountWithdraw">
                    <div class="card-content">
                        <div class="form-group required">
                            <label class="control-label">Member</label>
                            <basic-select placeholder="Select Name or Member ID"
                                          :options="members"
                                          :selected-option="member"
                                          @select="handleOnSelectMember"
                                          :is-error="isError">

                            </basic-select>
                        </div>
                        <div class="form-group required">
                            <label for="amount" class="control-label">Amount</label>
                            <input type="text"
                                   name="amount"
                                   id="amount"
                                   v-model="withdraw.amount"
                                   v-validate="withdrawValidation.amount"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('amount')">
                                {{ errors.first('amount') }}
                             </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit"
                                class="btn btn-outline btn-info btn-wd">সঞ্চয় উত্তোলন
                        </button>
                        <button type="submit" @click.prevent="cancel"
                                class="btn btn-outline btn-danger btn-wd"
                                style="margin-left: 15px">বাতিল
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue'
import isEmpty from 'lodash/isEmpty'
import VeeValidate from 'vee-validate'
import {BasicSelect} from 'vue-search-select'
import 'vue-search-select/dist/VueSearchSelect.css'

let veeCustomMessage = {
    en: {
        custom: {
            member_id: {
                required: 'Member ID field is required'
            },
            amount: {
                required: 'Savings amount field is required',
                numeric: 'Savings amount field must be numeric number',
                min: 'Savings amount field must be at least 2 number',
            },
        }
    }
};

let withdrawObj = {
    member_id: '',
    amount: '',
};
Vue.use(VeeValidate, {
    dictionary: veeCustomMessage,
    fieldsBagName: withdrawObj
});

export default {
    name: "Withdraw",
    components: {
        BasicSelect
    },
    data() {
        return {
            members: [],
            member: {
                value: '',
                text: ''
            },
            withdraw: withdrawObj,
            withdrawValidation: {
                amount: {
                    required: true,
                    numeric: true,
                    min: 2
                },
                member_id: {
                    required: true
                },
            }
        }
    },
    methods: {
        handleGetAllMember() {
            axios.get(this.$env.BACKEND_API + 'admin/accounting/total/saving/member/list')
                .then(res => {
                    let members = res.data;
                    members.forEach((member, index) => {
                        let memberObj = {};
                        memberObj.value = member.member.id;
                        memberObj.text = member.member.name + ' ' + member.member.member_id;
                        this.members.push(memberObj);
                    })
                })
                .catch(err => {
                    this.$notification.error(this, 'Error', err.response.data);
                })
        },
        getError(fieldName) {
            return this.errors.first(fieldName)
        },
        handleOnSelectMember(items) {
            this.member = items;
            this.withdraw.member_id = items.value;
        },
        handleResetSavingsWithdrawForm() {
            this.member = {};
            this.withdraw.member_id = '';
            this.withdraw.amount = '';
            this.$router.push({name: 'TotalSavingsList'});
        },
        cancel() {
            this.handleResetSavingsWithdrawForm();
        },
        handleSavingsAmountWithdraw() {
            this.$validator.validateAll().then(isValid => {
                if (isValid) {
                    if (this.withdraw.member_id !== '') {
                        let formData = new FormData();
                        formData.append('member_id', this.withdraw.member_id);
                        formData.append('amount', this.withdraw.amount);
                        axios.post(this.$env.BACKEND_API + 'admin/accounting/total/saving/withdraw', formData)
                            .then(res => {
                                if (res.data.status === 'validation' || res.data.status === 'failed') {
                                    this.$notification.error(this, 'Error', res.data.message);
                                } else if (res.data.status === 'success') {
                                    this.$notification.notify(this, 'Success', res.data.message);
                                    this.handleResetSavingsWithdrawForm();
                                } else {
                                    this.$notification.error(this, 'Error', 'Somethings went wrong');
                                }
                            })
                            .catch(err => {
                                this.$notification.notifyError(this, err.response.data);
                            })

                    } else {
                        this.$notification.error(this, 'Error', 'প্রথমে একটি মেম্বার সিলেক্ট করুন');
                    }
                }
            })
        }
    },
    mounted() {
        this.handleGetAllMember();
    },
    computed: {
        isError() {
            return isEmpty(this.member.value)
        }
    },
}
</script>

<style scoped>

</style>
