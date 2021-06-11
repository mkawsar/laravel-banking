<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">হোম</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'MemberLoanList'}">লোন লিস্ট</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ title }}</li>
            </ol>
        </nav>
        <div class="col-md-12">
            <div class="card">
                <form autocomplete="off">
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
                                   v-model="loan.amount"
                                   v-validate="loanValidations.amount"
                                   class="form-control">
                            <span class="text-danger" v-show="errors.has('amount')">
                                {{ errors.first('amount') }}
                             </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" @click.prevent="handleMemberPaidLoan"
                                class="btn btn-outline btn-info btn-wd">লোন প্রদান
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

let loanObj = {
    member_id: '',
    amount: '',
};
Vue.use(VeeValidate, {
    dictionary: veeCustomMessage,
    fieldsBagName: loanObj
});

export default {
    name: "MemberLoanAdd",
    data() {
        return {
            loan: loanObj,
            title: '',
            members: [],
            member: {
                value: '',
                text: ''
            },
            loanValidations: {
                amount: {
                    required: true,
                    numeric: true,
                    min: 2
                },
                member_id: {
                    required: true
                },
            },
        }
    },
    components: {
        BasicSelect
    },
    methods: {
        handleInitialData() {
            this.title = this.$route.meta.title;
            axios.get(this.$env.BACKEND_API + 'admin/member/list/without/pagination')
                .then(res => {
                    let members = res.data;
                    members.forEach((member, index) => {
                        let memberObj = {};
                        memberObj.value = member.id;
                        memberObj.text = member.name + ' ' + member.member_id;
                        this.members.push(memberObj);
                    })
                })
                .catch(err => {
                    this.$notification.error(this, 'Error', err.response.data);
                })
        },
        handleOnSelectMember(items) {
            this.member = items;
            this.loan.member_id = items.value;
        },
        handleResetMemberDailySavingsForm() {
            this.member = {};
            this.loan.member_id = '';
            this.loan.amount = '';
        },
        cancel() {
            this.$router.push({name: 'MemberLoanList'});
            this.handleResetMemberDailySavingsForm();
        },
        handleMemberPaidLoan() {
            this.disabled = true;
            this.$validator.validateAll().then(isValid => {
                if (isValid) {
                    if (this.loan.member_id !== '') {
                        let formData = new FormData();
                        formData.append('member_id', this.loan.member_id);
                        formData.append('loan_amount', this.loan.amount);
                        axios.post(this.$env.BACKEND_API + 'admin/accounting/loan/member/add', formData)
                            .then(res => {
                                if (res.data.status === 'validation' || res.data.status === 'failed') {
                                    this.$notification.error(this, 'Error', res.data.message);
                                } else if (res.data.status === 'success') {
                                    this.$notification.notify(this, 'Success', res.data.message);
                                    this.cancel();
                                } else {
                                    this.$notification.error(this, 'Error', 'Somethings went wrong');
                                }
                            })
                            .catch(err => {
                                this.$notification.notifyError(this, err.response.data);
                                this.disabled = false;
                            })
                    } else {
                        this.$notification.error(this, 'Error', 'প্রথমে একটি মেম্বার সিলেক্ট করুন');
                    }
                }
            });
        },
    },
    mounted() {
        this.handleInitialData();
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
