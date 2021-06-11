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
                    <router-link :to="{name: 'DailySavingsCreate'}" class="btn btn-outline btn-success">
                        দৈনিক সঞ্চয় যোগ
                    </router-link>
                </h4>
            </div>
            <div class="card-content row">
                <div class="col-sm-12">
                    <template>
                        <div class="filter-bar" style="margin-bottom: 10px">
                            <form class="form-inline" @submit.prevent="doFilter">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <input type="text"
                                               v-model="filterText"
                                               class="form-control" placeholder="Name or Member ID">
                                    </div>
                                    <div class="col-xs-5">
                                        <datepicker
                                            :format="format"
                                            v-model="filterDate"
                                            input-class="form-control"/>
                                    </div>
                                    <div class="col-xs-2">
                                        <button class="btn btn-primary btn-sm">Go</button>
                                        <button class="btn btn-default btn-sm" @click.prevent="resetFilter">Reset</button>
                                    </div>
                                </div>
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
                                            content: 'Delete this member daily savings',
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
import Datepicker from "vuejs-datepicker/dist/vuejs-datepicker.esm.js";

import {MessageBox} from 'element-ui';

Vue.prototype.$confirm = MessageBox.confirm;

export default {
    name: "DailySavingList",
    components: {
        Vuetable,
        VuetablePagination,
        VuetablePaginationInfo,
        datepicker: Datepicker
    },
    data() {
        return {
            url: this.$env.BACKEND_API + 'admin/accounting/daily/saving/list',
            css: VuetableCssConfig,
            filterText: '',
            filterDate: '',
            moreParams: {},
            format: 'yyyy-MM-dd',
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
                    name: 'amount',
                    title: 'জমা টাকা',
                    titleClass: 'text-center',
                    dataClass: 'text-center',
                },
                {
                    name: 'saving_date',
                    title: 'জমাদানের তারিখ',
                    titleClass: 'text-center',
                    dataClass: 'text-center',
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
            if (this.filterText === '' && this.filterDate === '') {
                this.url = this.$env.BACKEND_API + 'admin/accounting/daily/saving/list';
            } else {
                let date = '';
                if (this.filterDate !== '') {
                    date = this.$moment(this.filterDate).format('Y-MM-DD H:m:s');
                }
                this.url = this.$env.BACKEND_API + `admin/accounting/daily/saving/list/search?search=${this.filterText}&date=${date}`;
            }
        },
        resetFilter() {
            this.filterText = '';
            this.filterDate = '';
            this.url = this.$env.BACKEND_API + 'admin/accounting/daily/saving/list'
        },
        handleMemberDailySavingsDelete(name, savingsID) {
            this.$confirm('This will delete the user "' + name + '". Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                axios.delete(this.$env.BACKEND_API + `admin/accounting/daily/saving/${savingsID}/delete`)
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
    }
}
</script>

<style scoped>

</style>
