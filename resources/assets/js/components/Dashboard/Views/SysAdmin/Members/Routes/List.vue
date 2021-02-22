<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">হোম</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">মেম্বার রুট লিস্ট</li>
            </ol>
        </nav>
        <div class="col-md-12 card">
            <form autocomplete="off">
                <div class="card-header">
                    <h4 class="card-title">
                        Enter Member Route Details Below
                    </h4>
                </div>
                <div class="card-content">
                    <div class="form-group required">
                        <label for="name" class="control-label">মেম্বার রুট নাম</label>
                        <input type="text"
                               name="name"
                               id="name"
                               v-validate="routeValidations.name"
                               v-model="route.name"
                               placeholder="মেম্বার রুট নাম"
                               class="form-control">
                        <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" @click.prevent="handleCreateNewMemberRoute"
                            class="btn btn-outline btn-info btn-wd">সাবমিট
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-12 card">
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
                                        @click.prevent="handleDeleteRoute(props.rowData)"
                                        v-tooltip="{
                                            content: 'Delete this member route',
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
import Vue from 'vue'
import VeeValidate from 'vee-validate'
import Vuetable from "vuetable-2/src/components/Vuetable";
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetablePaginationInfo from "vuetable-2/src/components/VuetablePaginationInfo";
import VuetableCssConfig from "~/plugins/VuetableCssConfig";

import {MessageBox} from 'element-ui';

Vue.prototype.$confirm = MessageBox.confirm;

let veeCustomMessage = {
    en: {
        custom: {
            name: {
                required: 'Full member route name field is required',
            },
        }
    }
};

let routeObj = {
    name: ''
};

Vue.use(VeeValidate, {
    dictionary: veeCustomMessage,
    fieldsBagName: routeObj
});
export default {
    name: "List",
    data() {
        return {
            route: routeObj,
            routeValidations: {
                name: {
                    required: true
                },
            },
            url: this.$env.BACKEND_API + 'admin/member/route/list',
            css: VuetableCssConfig,
            filterText: '',
            moreParams: {},
            tableRowsFields: [
                {
                    name: 'name',
                    title: 'নাম'
                },
                {
                    name: 'creator',
                    title: 'ক্রিয়েটর',
                    callback: function (user) {
                        return user.name
                    }
                },
                {
                    name: '__slot:actions',
                    title: 'অ্যাকশন',
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
        handleCreateNewMemberRoute() {
            this.$validator.validateAll().then(isValid => {
                if (isValid) {
                    let formData = new FormData();
                    formData.append('name', this.route.name);
                    axios.post(this.$env.BACKEND_API + 'admin/member/route/create', formData)
                        .then(res => {
                            if (res.data.status === 'validation' || res.data.status === 'failed') {
                                this.$notification.error(this, 'Error', res.data.message);
                            } else if (res.data.status === 'success') {
                                this.$notification.notify(this, 'Success', res.data.message);
                                Vue.nextTick(() => this.$refs.vuetable.refresh())
                                this.route.name = '';
                            } else {
                                this.$notification.error(this, 'Error', 'Somethings went wrong');
                            }
                        })
                        .catch(err => {
                            this.$notification.notifyError(this, err.response.data);
                        })
                }
            });
        },
        getError(fieldName) {
            return this.errors.first(fieldName)
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
                this.url = this.$env.BACKEND_API + 'admin/member/list';
            } else {
                this.url = this.$env.BACKEND_API + `admin/member/search?search=${this.filterText}`;
            }
        },
        resetFilter() {
            this.filterText = '';
            this.url = this.$env.BACKEND_API + 'admin/member/list'
        },
        handleDeleteRoute(route) {
            this.$confirm('This will delete the user "' + route.name + '". Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                axios.delete(this.$env.BACKEND_API + `admin/member/route/${route.id}/delete`)
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
