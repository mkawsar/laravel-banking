<template>
    <div>
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <router-link :to="{name: 'MemberList'}">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="ti-user"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        <p>মেম্বার সংখ্যা</p>
                                        {{ members }}
                                    </div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-content">
                        <router-link :to="{name: 'MemberRouteList'}">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-success text-center">
                                        <i class="fa fa-road"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        <p>রুট সংখ্যা</p>
                                        {{ routes }}
                                    </div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
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
                                    <p>মোট সঞ্চয়</p>
                                    {{ savings }}
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
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>মাসিক লোনের পরিমান</p>
                                    {{ monthlyLoan }}
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
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>মাসিক মুনাফা পরিমান</p>
                                    {{ monthlyLoanInterest }}
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
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>মাসিক লোন সংগ্রহ</p>
                                    {{ monthlyLoanPayment }}
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
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>বাৎসরিক লোণের পরিমান</p>
                                    {{ yearlyLoan }}
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
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>বাৎসরিক মুনাফা পরিমান</p>
                                    {{ yearlyLoanInterest }}
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
                                    <i class="fa fa-dollar"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers">
                                    <p>লোন বকেয়া</p>
                                    {{ remainingLoanAmount }}
                                </div>
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
            members: 0,
            routes: 0,
            savings: 0,
            yearlyLoan: 0,
            yearlyLoanInterest: 0,
            monthlyLoan: 0,
            monthlyLoanInterest: 0,
            monthlyLoanPayment: 0,
            remainingLoanAmount: 0,
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
                    this.routes = res.data.routes;
                    this.savings = res.data.savings;
                    this.yearlyLoan = res.data.yearly_loan_amount;
                    this.yearlyLoanInterest = res.data.yearly_loan_interest_amount;
                    this.monthlyLoan = res.data.monthly_loan_amount;
                    this.monthlyLoanInterest = res.data.monthly_loan_interest_amount;
                    this.monthlyLoanPayment = res.data.monthly_loan_payment_amount;
                    this.remainingLoanAmount = res.data.total_remaining_loan;
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
