<template>
    <div>
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
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-warning text-center">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>মোট লোন</p>
                                    {{loan.loan_amount + ' TK'}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-success text-center">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>দৈনিক কিস্তি</p>
                                    {{loan.installment_amount + ' TK'}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-success text-center">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>মোট কিস্তি</p>
                                    {{total_installment}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-warning text-center">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>পরিশোধ</p>
                                    {{paid_amount + ' TK'}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-success text-center">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>বকেয়া লোন</p>
                                    {{due_amount + ' TK'}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-success text-center">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>বাকি কিস্তি</p>
                                    {{due_installment}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 card">
                <div class="card-header">
                    <h4 class="card-title">
                        <button class="btn btn-outline btn-success" @click.prevent="handleSubmitPaymentLoanInstallment(loan)">
                            দৈনিক কিস্তি
                        </button>
                    </h4>
                </div>
                <div class="card-content row">
                    <div class="col-sm-12">
                        <template>
                            <vuetable ref="vuetable"
                                      :api-url="url"
                                      :http-options="headerOptions"
                                      :fields="tableRowsFields"
                                      pagination-path=""
                                      :css="css.table"
                                      @vuetable:loading="showLoader"
                                      @vuetable:loaded="hideLoader"
                                      @vuetable:pagination-data="onPaginationData"
                            >
                                <template slot="actions" slot-scope="props">
                                    <button class="btn btn-simple btn-xs btn-danger btn-icon remove"
                                            @click.prevent="handleDeleteMemberLoan(props.rowData.id)"
                                            v-tooltip="{
                                            content: 'Delete this member loan',
                                            placement: 'top-center',
                                            classes: ['info'],
                                            targetClasses: ['it-has-a-tooltip'],
                                            offset: 10,}">
                                        <i class="ti-close"></i>
                                    </button>
                                </template>
                            </vuetable>
                            <div class="vuetable-pagination ui basic segment grid">
                                <vuetable-pagination-info ref="paginationInfo">
                                </vuetable-pagination-info>

                                <vuetable-pagination ref="pagination" :css="css.pagination"
                                                     @vuetable-pagination:change-page="onChangePage"
                                ></vuetable-pagination>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import Vuetable from "vuetable-2/src/components/Vuetable";
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetablePaginationInfo from "vuetable-2/src/components/VuetablePaginationInfo";
import VuetableCssConfig from "~/plugins/VuetableCssConfig";

import {Form, MessageBox} from 'element-ui';

Vue.prototype.$confirm = MessageBox.confirm;

export default {
    components: {
        Vuetable,
        VuetablePagination,
        VuetablePaginationInfo
    },
    name: "MemberLoanPaymentList",
    data() {
        return {
            title: '',
            url: this.$env.BACKEND_API + `admin/accounting/loan/${this.$route.params.loanID}/${this.$route.params.memberID}/payment/list`,
            css: VuetableCssConfig,
            filterText: '',
            moreParams: {},
            tableRowsFields: [
                {
                    name: 'member',
                    title: 'নাম',
                    callback: function (member) {
                        return member.name;
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center',
                },
                {
                    name: 'member',
                    title: 'মেম্বার আইডি',
                    callback: function (member) {
                        return member.member_id;
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center',
                },
                {
                    name: 'member.route',
                    title: 'রুট নাম',
                    callback: function (route) {
                        return route.name;
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'creator',
                    title: 'লোন দাতা',
                    callback: function (creator) {
                        return creator.name;
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'payment_date',
                    title: 'জমার তারিখ',
                    callback: function (date) {
                        return date;
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'payment_amount',
                    title: 'জমার পরিমান',
                    callback: function (amount) {
                        return amount + ' TK';
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'installment_count',
                    title: 'কিস্তি সংখ্যা',
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                }
            ],
            headerOptions: {headers: {Authorization: 'Bearer ' + localStorage.getItem('token')}},
            loan: {},
            total_installment: 142,
            paid_amount: 0,
            due_amount: 0,
            due_installment: 0
        }
    },
    methods: {
        handleInitialData() {
            this.title = this.$route.meta.title;
            axios.get(this.$env.BACKEND_API + `admin/accounting/loan/${this.$route.params.loanID}/details`)
            .then(res => {
                let response = res.data;
                this.loan = response.loan;
                this.paid_amount = response.paid_amount;
                this.due_amount = response.due_amount;
                this.due_installment = response.due_installment;
            })
            .catch(err => {
                this.$notification.error(this, 'Error', err.response.data);
            })
        },
        onPaginationData(paginationData) {
            this.$refs.pagination.setPaginationData(paginationData);
            this.$refs.paginationInfo.setPaginationData(paginationData)
        },
        onChangePage(page) {
            this.$refs.vuetable.changePage(page)
        },
        showLoader() {
            this.loading = true;
        },
        hideLoader() {
            this.loading = false;
        },
        handleSubmitPaymentLoanInstallment(loan) {
            this.$confirm('আপনি আপনার দৈনিক "' + loan.installment_amount + '" টাকা কিস্তি দিচ্ছেন. রাজি?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                let formData = new FormData();
                formData.append('amount', loan.installment_amount);
                axios.post(this.$env.BACKEND_API + `admin/accounting/loan/${loan.id}/store/payment`, formData)
                .then(res => {
                     if (res.data.status === 'validation' || res.data.status === 'failed') {
                        this.$notification.error(this, 'Error', res.data.message);
                    } else if (res.data.status === 'success') {
                        this.$notification.notify(this, 'Success', res.data.message);
                        this.$refs.vuetable.refresh();
                        this.handleInitialData();
                    } else {
                        this.$notification.error(this, 'Error', 'Somethings went wrong');
                    }
                })
                .catch(err => {
                    this.$notification.notifyError(this, err.response.data);
                })
            })
            .catch(() => {

            })
        }
    },
    mounted() {
        this.handleInitialData();
    }
}

</script>
