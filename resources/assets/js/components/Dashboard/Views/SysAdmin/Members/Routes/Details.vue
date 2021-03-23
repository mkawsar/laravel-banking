<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">হোম</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'MemberRouteList'}">মেম্বার রুট লিস্ট</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">মেম্বার রুট ডিটেলস</li>
            </ol>
        </nav>
        <div class="col-md-12 card">
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
                                <button
                                    type="button"
                                    class="btn btn-default btn-sm pull-right"
                                    @click="handleDownload"
                                    v-if="length > 0">Download</button>
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
                            <template slot="picture" slot-scope="props">
                                <img :src="props.rowData.picture" class="table-img" alt="">
                            </template>
                            <template slot="actions" slot-scope="props">
                                <button class="btn btn-simple btn-xs btn-danger btn-icon remove"
                                        @click.prevent="handleMemberDelete(props.rowData.name, props.rowData.id)"
                                        v-tooltip="{
                                                    content: 'Delete this member',
                                                    placement: 'top-center',
                                                    classes: ['info'],
                                                    targetClasses: ['it-has-a-tooltip'],
                                                    offset: 10,}">
                                    <i class="ti-close"></i>
                                </button>
                                <router-link :to="{name: 'MemberEdit', params: { memberID: props.rowData.id }}"
                                             class="btn btn-simple btn-xs btn-success btn-icon"
                                             v-tooltip="{
                                                    content: 'Edit this member',
                                                    placement: 'top-center',
                                                    classes: ['info'],
                                                    targetClasses: ['it-has-a-tooltip'],
                                                    offset: 10,}">
                                    <i class="ti-pencil"></i>
                                </router-link>
                                <router-link :to="{name: 'MemberDetails', params: { memberID: props.rowData.id }}"
                                             class="btn btn-simple btn-xs btn-warning btn-icon"
                                             v-tooltip="{
                                                    content: 'Details this member',
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
    name: "Details",
    components: {
        Vuetable,
        VuetablePagination,
        VuetablePaginationInfo,
    },
    data() {
        return {
            url: this.$env.BACKEND_API + `admin/member/route/${this.$route.params.routeID}/details`,
            css: VuetableCssConfig,
            filterText: '',
            moreParams: {},
            tableRowsFields: [
                {
                    name: 'member_id',
                    title: 'Member ID'
                },
                {
                    name: 'route',
                    title: 'Member Route',
                    callback: function (route) {
                        return route.name;
                    }
                },
                {
                    name: 'name',
                    title: 'Name'
                },
                {
                    name: 'phone',
                    title: 'Mobile'
                },
                {
                    name: 'father_name',
                    title: 'Father Name'
                },
                {
                    name: 'nominee_name',
                    title: 'Nominee Name'
                },
                {
                    name: '__slot:picture',
                    title: 'Picture',
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
            headerOptions: {
                headers: {Authorization: 'Bearer ' + localStorage.getItem('token')}
            },
            length: 0,
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
        handleMemberDelete(name, memberID) {
            this.$confirm('This will delete the user "' + name + '". Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                axios.delete(this.$env.BACKEND_API + `admin/member/${memberID}/delete`)
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
        },
        doFilter() {
            if (this.filterText === '') {
                this.url = this.$env.BACKEND_API + `admin/member/route/${this.$route.params.routeID}/search`;
            } else {
                this.url = this.$env.BACKEND_API + `admin/member/route/${this.$route.params.routeID}/search?search=${this.filterText}`;
            }
        },
        resetFilter() {
            this.filterText = '';
            this.url = this.$env.BACKEND_API + `admin/member/route/${this.$route.params.routeID}/search`;
        },
        handleDownload() {
            console.log(this.$route.params.routeID)
            // console.log(window.location.host + this.$env.BACKEND_API + `admin/member/route/${this.$route.params.routeID}/download`);
            // window.open('http://www.google.com');
            window.location.href = this.$env.BACKEND_API + `admin/member/route/${this.$route.params.routeID}/download`;
        }
    },
    mounted() {
        axios.get(this.$env.BACKEND_API + `admin/member/route/${this.$route.params.routeID}/details`)
            .then(res => {
                const data = res.data.data;
                this.length = data.length;
            })
            .catch(err => {
                this.$notification.error(this, 'Error', err.response.data);
            })
    }
}
</script>

<style scoped>

</style>
