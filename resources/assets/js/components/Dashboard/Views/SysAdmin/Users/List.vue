<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">Home</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">List</li>
            </ol>
        </nav>
        <div class="col-md-12 card">
            <div class="card-header">
                <h4 class="card-title">
                    <router-link
                        :to="{name: 'UserCreate'}"
                        class="btn btn-outline btn-success">Add User
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
                                       placeholder="Name">
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
                                        @click.prevent="handleUserDelete(props.rowData.name, props.rowData.id)"
                                        v-tooltip="{
                                            content: 'Delete this user',
                                            placement: 'top-center',
                                            classes: ['info'],
                                            targetClasses: ['it-has-a-tooltip'],
                                            offset: 10,}">
                                    <i class="ti-close"></i>
                                </button>
                                <router-link :to="{name: 'UserEdit', params: { userID: props.rowData.id }}"
                                             class="btn btn-simple btn-xs btn-danger btn-icon"
                                             v-tooltip="{
                                            content: 'Edit this user',
                                            placement: 'top-center',
                                            classes: ['info'],
                                            targetClasses: ['it-has-a-tooltip'],
                                            offset: 10,}">
                                    <i class="ti-pencil"></i>
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
    data() {
        return {
            url: this.$env.BACKEND_API + 'admin/user/list',
            css: VuetableCssConfig,
            filterText: '',
            moreParams: {},
            tableRowsFields: [
                {
                    name: 'name',
                    title: 'Name'
                },
                {
                    name: 'email',
                    title: 'Email'
                },
                {
                    name: 'role',
                    title: 'Role',
                    callback: function (item) {
                        return item.name;
                    }
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
        VuetablePaginationInfo,
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
                this.url = this.$env.BACKEND_API + 'admin/user/list';
            } else {
                this.url = this.$env.BACKEND_API + `admin/user/search?search=${this.filterText}`;
            }
        },
        resetFilter() {
            this.filterText = '';
            this.url = this.$env.BACKEND_API + 'admin/user/list'
        },
        handleUserDelete(name, userId) {
            this.$confirm('This will delete the user "' + name + '". Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                axios.delete(this.$env.BACKEND_API + `admin/user/${userId}/delete`)
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
                    this.$notification.error(this, 'Error', 'Somethings went wrong');
                })
        }
    }
}
</script>
