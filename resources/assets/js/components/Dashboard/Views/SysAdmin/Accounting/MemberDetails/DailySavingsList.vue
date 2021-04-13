<template>
    <div>
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-css">
                    <li class="breadcrumb-item">
                        <router-link :to="{name: 'Overview'}">হোম</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="{name: 'TotalSavingsList'}">মোট সঞ্চয় লিস্ট</router-link>
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
                                    <p>মোট সঞ্চয়</p>
                                    {{ savings.savings_amount + ' TK' }}
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
                                <div class="icon-big icon-danger text-center">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>মোট উত্তোলন</p>
                                    {{ savings.withdraw_amount + ' TK' }}
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
                                    <p>অবশিষ্ট সঞ্চয়</p>
                                    {{ savings.remain_amount + ' TK' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 card">
                <div class="card-content row">
                    <div class="col-sm-12">
                        <template>
                            <div class="filter-bar" style="margin-bottom: 10px">
                                <form class="form-inline">
                                    <label>Search for:</label>
                                    <input type="date"
                                           v-model="filterText"
                                           class="form-control" placeholder="Name or Member ID">
                                    <button class="btn btn-primary btn-sm" @click.prevent="doFilter(filterText)">Go
                                    </button>
                                    <button class="btn btn-default btn-sm" @click.prevent="resetFilter">Reset</button>
                                    <button class="btn btn-success btn-sm pull-right"
                                            type="button"
                                            @click="handleMemberSavingsListDownload">Download
                                    </button>
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
import Vuetable from "vuetable-2/src/components/Vuetable";
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetablePaginationInfo from "vuetable-2/src/components/VuetablePaginationInfo";
import VuetableCssConfig from "~/plugins/VuetableCssConfig";

export default {
    name: "DailySavingsList",
    components: {
        Vuetable,
        VuetablePagination,
        VuetablePaginationInfo
    },
    data() {
        return {
            'title': '',
            savings: {
                savings_amount: 0,
                withdraw_amount: 0,
                remain_amount: 0
            },
            config: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
                showClear: true,
                showClose: true,
            },
            url: this.$env.BACKEND_API + `admin/accounting/member/${this.$route.params.memberID}/daily/savings/list`,
            css: VuetableCssConfig,
            filterText: '',
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
                }
            ],
            headerOptions: {headers: {Authorization: 'Bearer ' + localStorage.getItem('token')}},
        }
    },
    methods: {
        handleInitialData() {
            this.title = this.$route.meta.title;
            axios.get(this.$env.BACKEND_API + `admin/accounting/member/${this.$route.params.memberID}/savings/details`)
                .then(res => {
                    let data = res.data;
                    this.savings.savings_amount = data.savings_amount;
                    this.savings.withdraw_amount = data.withdraw_amount;
                    this.savings.remain_amount = data.remain_amount;
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
        doFilter() {
            if (this.filterText === '') {
                this.url = this.$env.BACKEND_API + `admin/accounting/member/${this.$route.params.memberID}/daily/savings/list`;
            } else {
                let date = this.$moment(this.filterText).format('Y-MM-DD H:m:s');
                this.url = this.$env.BACKEND_API + `admin/accounting/member/${this.$route.params.memberID}/daily/savings/list?search=${date}`;
            }
        },
        resetFilter() {
            this.filterText = '';
            this.url = this.$env.BACKEND_API + `admin/accounting/member/${this.$route.params.memberID}/daily/savings/list`
        },
        handleMemberSavingsListDownload() {
            window.location.href = this.$env.BACKEND_API + `admin/accounting/member/${this.$route.params.memberID}/daily/savings/download`;
        }
    },
    mounted() {
        this.handleInitialData();
    }
}
</script>

<style scoped>

</style>
