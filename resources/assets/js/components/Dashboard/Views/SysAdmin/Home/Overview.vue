<template>
    <div>
        <!--<div class="row">
            <div class="col-lg-4 col-sm-6">
                <circle-chart-card :percentage="70"
                                   title="Dashboard"
                                   description="Daily Enrolled Member"
                                   color="blue">
                </circle-chart-card>
            </div>

            <div class="col-lg-4 col-sm-6">
                <circle-chart-card :percentage="34"
                                   title="Ass"
                                   description="Completed"
                                   color="green">
                </circle-chart-card>
            </div>

            <div class="col-lg-4 col-sm-6">
                <circle-chart-card :percentage="62"
                                   title="New Orders"
                                   description="Out of total number"
                                   color="orange">
                </circle-chart-card>
            </div>
        </div>-->

        <!--<div class="row">
            <div class="col-lg-12">
                <el-table :data="tableData">
                    <el-table-column type="index">

                    </el-table-column>
                    <el-table-column prop="configuration_name" label="Configuration Name">
                    </el-table-column>
                    <el-table-column prop="name" label="Customer Name">
                    </el-table-column>
                    <el-table-column prop="order_id" label="Order ID">
                    </el-table-column>
                    <el-table-column prop="Status" label="Status">
                    </el-table-column>
                    <el-table-column prop="create_date" label="Create Date">
                    </el-table-column>
                    <el-table-column prop="configured_date" label="Configured Date">
                    </el-table-column>
                    <el-table-column prop="price" label="Estimated Price">
                    </el-table-column>
                    <el-table-column label="Actions">
                        <template slot-scope="props">
                            <a class="btn btn-simple btn-warning btn-xs btn-icon view"
                               @click="handleEdit(props.$index, props.row)">
                                <i class="ti-pencil-alt"></i>
                            </a>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>-->
    </div>
</template>

<script>
    import CircleChartCard from '../../../../UIComponents/Cards/CircleChartCard'
    import StatsCard from '../../../../UIComponents/Cards/StatsCard';
    import ChartCard from '../../../../UIComponents/Cards/ChartCard';
    import Loading from '../../../Layout/LoadingMainPanel';
    import {Table, TableColumn} from 'element-ui'
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
        data: function () {
            return {
                statsCards: [
                    {
                        type: 'warning',
                        icon: 'ti-server',
                        title: 'Capacity',
                        value: '105GB',
                        footerText: 'Updated now',
                        footerIcon: 'ti-reload'
                    },
                    {
                        type: 'success',
                        icon: 'ti-wallet',
                        title: 'Revenue',
                        value: '$1,345',
                        footerText: 'Last day',
                        footerIcon: 'ti-calendar'
                    },
                    {
                        type: 'danger',
                        icon: 'ti-pulse',
                        title: 'Errors',
                        value: '23',
                        footerText: 'In the last hour',
                        footerIcon: 'ti-timer'
                    },
                    {
                        type: 'info',
                        icon: 'ti-twitter-alt',
                        title: 'Followers',
                        value: '+45',
                        footerText: 'Updated now',
                        footerIcon: 'ti-reload'
                    }
                ],
                earningsChart: {
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'April', 'May', 'June'],
                        series: [
                            [230, 340, 400, 300, 570, 500, 800]
                        ]
                    },
                    options: {
                        showPoint: false,
                        lineSmooth: true,
                        height: '210px',
                        axisX: {
                            showGrid: false,
                            showLabel: true
                        },
                        axisY: {
                            offset: 40,
                            showGrid: false
                        },
                        low: 0,
                        high: 'auto',
                        classNames: {
                            line: 'ct-line ct-green'
                        }
                    }
                },
                tableData: [{
                    configuration_name: 'Cloudly',
                    name: 'Criestiano Ronaldo',
                    order_id: 'Q-31577',
                    Status: 'Initiated',
                    create_date: '8 Mar 2018 GMT',
                    configured_date: '8 Mar 2018 GMT',
                    price: '$ 18854',
                    active: false
                }, {
                    configuration_name: 'Horoppa',
                    name: 'Andrew Mike',
                    order_id: 'Q-34457',
                    Status: 'Initiated',
                    create_date: '7 Mar 2018 GMT',
                    configured_date: '9 Mar 2018 GMT',
                    price: '$ 7882',
                    active: false
                }, {
                    configuration_name: 'Configuration 1',
                    name: 'Requalium Andin',
                    order_id: 'Q-34497',
                    Status: 'Configured',
                    create_date: '7 Mar 2018 GMT',
                    configured_date: '9 Mar 2018 GMT',
                    price: '8 Mar 2018 GMT',
                    active: false
                }, {
                    configuration_name: 'Red Oak Plan 1',
                    name: 'Mike Monday',
                    order_id: 'Q-34497',
                    Status: 'Configured',
                    create_date: '7 Mar 2018 GMT',
                    configured_date: '9 Mar 2018 GMT',
                    price: '$ 49000',
                    active: false
                }
                ]

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
            }
        }
    }
</script>

<style scoped>

</style>
