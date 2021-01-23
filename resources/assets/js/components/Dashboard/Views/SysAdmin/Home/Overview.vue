<template>
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-warning text-center">
                                <i class="ti-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>{{ 'Member'| pluralize(members) }}</p>
                                {{ members }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-success text-center">
                                <i class="ti-wallet"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Revenue</p>
                                $1,345
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-danger text-center">
                                <i class="ti-pulse"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Errors</p>
                                23
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-info text-center">
                                <i class="ti-twitter-alt"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Followers</p>
                                +45
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CircleChartCard from '../../../../UIComponents/Cards/CircleChartCard'
import StatsCard from '../../../../UIComponents/Cards/StatsCard';
import ChartCard from '../../../../UIComponents/Cards/ChartCard';
import Loading from '../../../Layout/LoadingMainPanel';
import {Table, TableColumn} from 'element-ui'
import pluralize from '~/plugins/pluralize'
import Vue from 'vue'

Vue.use(Table);
Vue.use(TableColumn);

export default {
    components: {
        StatsCard,
        ChartCard,
        CircleChartCard
    },
    /**
     * Chart data used to render stats, charts. Should be replaced with server data
     */
    data() {
        return {
            members: 0
        }
    },
    methods: {
        handleLike(index, row) {
            alert(`Your clicked on Like button`)
        },
        handleEdit(index, row) {
            this.$router.push({name: 'ProjectInformation'});
        },
        handleDelete(index, row) {
            alert(`Your want to delete ${row.name}`)
        },
        handleGetAllInfoCount() {
            axios.get(this.$env.BACKEND_API + 'admin/home/details')
                .then(res => {
                    this.members = res.data.members;
                })
                .catch(err => {
                    console.log(err);
                })
        }
    },
    mounted() {
        this.handleGetAllInfoCount();
    }
}
</script>

<style scoped>

</style>
