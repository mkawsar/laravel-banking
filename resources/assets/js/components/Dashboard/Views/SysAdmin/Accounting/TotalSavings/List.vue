<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">হোম</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">লিস্ট</li>
            </ol>
        </nav>
        <div class="col-md-12 card">
            <div class="card-header">
                <h4 class="card-title">
                    <router-link :to="{name: 'SavingsWithdraw'}" class="btn btn-outline btn-success">
                        সঞ্চয় উত্তোলন
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
                                        @click.prevent="handleMemberDailySavingsDelete(props.rowData.member.name, props.rowData.id)"
                                        v-tooltip="{
                                            content: 'Details',
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
    name: "TotalSavingsList",
    components: {
        Vuetable,
        VuetablePagination,
        VuetablePaginationInfo
    },
    data() {
        return {
            url: this.$env.BACKEND_API + 'admin/accounting/total/saving/list',
            css: VuetableCssConfig,
            filterText: '',
            filterDate: '',
            moreParams: {},
            tableRowsFields: [
                {
                    name: 'member',
                    title: 'নাম',
                    callback: function (member) {
                        return member.name;
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'member',
                    title: 'সদস্য নম্বর',
                    callback: function (member) {
                        return member.member_id;
                    },
                    titleClass: 'text-center',
                    dataClass: 'text-center'
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
                    name: 'amount',
                    title: 'মোট সঞ্চয়',
                    callback: function (amount) {
                        return amount + ' ' +'TK';
                    },
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
    methods: {
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
        onFilterSet(filterText) {
            this.moreParams = {
                'filter': filterText
            };
            Vue.nextTick(() => this.$refs.vuetable.refresh())
        },
        onFilterReset() {
            this.moreParams = {};
            Vue.nextTick(() => this.$refs.vuetable.refresh())
        },
        doFilter() {
            if (this.filterText === '') {
                this.url = this.$env.BACKEND_API + 'admin/accounting/total/saving/list';
            } else {
                this.url = this.$env.BACKEND_API + `admin/accounting/total/saving/list?search=${this.filterText}`;
            }
        },
        resetFilter() {
            this.filterText = '';
            this.url = this.$env.BACKEND_API + 'admin/accounting/total/saving/list'
        },
    }
}
</script>

<style scoped>

</style>
