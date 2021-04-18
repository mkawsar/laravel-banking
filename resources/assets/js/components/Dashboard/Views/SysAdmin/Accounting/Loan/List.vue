<template>
    <div>
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-css">
                    <li class="breadcrumb-item">
                        <router-link :to="{name: 'Overview'}">হোম</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ title }}</li>
                </ol>
            </nav>
            <div class="col-md-12 card">
                <div class="card-header">
                    <h4 class="card-title">
                        <router-link :to="{name: 'MemberLoanAdd'}" class="btn btn-outline btn-success">
                            লোন প্রদান
                        </router-link>
                    </h4>
                </div>
                <div class="card-content row">
                    <div class="col-sm-12">
                        <template>
                            <div class="filter-bar" style="margin-bottom: 10px">
                                <form class="form-inline">
                                    <label>Search for:</label>
                                    <input type="text"
                                           v-model="filterText"
                                           class="form-control"
                                           @keyup.enter.prevent="doFilter(filterText)"
                                           placeholder="Name or Member ID">
                                    <button class="btn btn-primary btn-sm" @click.prevent="doFilter(filterText)">Go</button>
                                    <button class="btn btn-default btn-sm" @click.prevent="resetFilter">Reset</button>
                                </form>
                            </div>
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
                                    <router-link :to="{name: 'MemberLoanPaymentList', params: { loanID: props.rowData.id, memberID: props.rowData.member_id }}"
                                        class="btn btn-simple btn-xs btn-success btn-icon remove"
                                            v-tooltip="{
                                            content: 'Payment list',
                                            placement: 'top-center',
                                            classes: ['info'],
                                            targetClasses: ['it-has-a-tooltip'],
                                            offset: 10,}">
                                        <i class="ti-eye"></i>
                                    </router-link>
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

import {MessageBox} from 'element-ui';

Vue.prototype.$confirm = MessageBox.confirm;
export default {
    name: "MemberLoanList",
    data() {
        return {
            title: '',
            url: this.$env.BACKEND_API + 'admin/accounting/loan/member/list',
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
                    titleClass: 'text-center'
                },
                {
                    name: 'member',
                    title: 'মেম্বার আইডি',
                    callback: function (member) {
                        return member.member_id;
                    },
                    titleClass: 'text-center'
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
                    name: 'loan_amount',
                    title: 'লোনের পরিমান',
                    callback: function (amount) {
                        return amount + ' TK';
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'interest_percentage',
                    title: 'শতকরা',
                    callback: function (amount) {
                        return amount + ' %';
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'installment',
                    title: 'কিস্তি',
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'installment_amount',
                    title: 'প্রতি কিস্তি',
                    callback: function (amount) {
                        return amount + ' TK';
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'interest_amount',
                    title: 'মুনাফা',
                    callback: function (amount) {
                        return amount + ' TK';
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'paid_amount',
                    title: 'পরিশোধ',
                    callback: function (amount) {
                        return amount + ' TK';
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'due_amount',
                    title: 'বকেয়া লোন',
                    callback: function (amount) {
                        return amount + ' TK';
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'due_installment',
                    title: 'বাকি কিস্তি',
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: '__slot:actions',
                    title: 'Actions',
                    titleClass: 'text-center',
                    dataClass: 'text-center',
                }
            ],
            headerOptions: {headers: {Authorization: 'Bearer ' + localStorage.getItem('token')}},
        }
    },
    components: {
        Vuetable,
        VuetablePagination,
        VuetablePaginationInfo
    },
    methods: {
        handleInitialData() {
            this.title = this.$route.meta.title;
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
        doFilter() {
            if (this.filterText === '') {
                this.url = this.$env.BACKEND_API + `admin/accounting/loan/member/list`;
            } else {
                this.url = this.$env.BACKEND_API + `admin/accounting/loan/member/list?search=${this.filterText}`;
            }
        },
        resetFilter() {
            this.filterText = '';
            this.url = this.$env.BACKEND_API + `admin/accounting/loan/member/list`
        },
        handleDeleteMemberLoan(loanID) {
            this.$confirm('This will delete the loan". Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                axios.delete(this.$env.BACKEND_API + `admin/accounting/loan/${loanID}/member/delete `)
                    .then(res => {
                        if (res.data.status === 'failed') {
                            this.$notification.error(this, 'Error', res.data.message);
                        } else if (res.data.status === 'success') {
                            this.$notification.notify(this, 'Success', res.data.message);
                            this.$refs.vuetable.refresh();
                        } else {
                            this.$notification.error(this, 'Error', 'Somethings went wrong');
                        }
                    })
                    .catch(err => {
                        this.$notification.error(this, 'Error', err.response.data);
                    })
            })
                .catch(() => {
                    //this.$notification.error(this, 'Error', 'Somethings went wrong');
                })
        }
    },
    mounted() {
        this.handleInitialData();
    }
}
</script>

<style scoped>

</style>
