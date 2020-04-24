<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header bg-success text-white text-center"><h4>Thống kê</h4></div>
                    <div class="card-body bg-dark text-white">
                       <div class="row text-dark mb-2">
                           <div class="col-6 col-md-6">
                              <div class="form-group">
                                  <div class="form-inline">
                                    <label for="Time" class="text-white mr-2">Ngày:</label>
                                    <datetime format="DD-MM-YYYY" v-model="val" id="Time"></datetime>
                                  </div>
                              </div>
                            </div>
                       </div>
                       <div class="row justify-content-center">
                           <div class="col-12 col-sm-8 col-md-6">
                               <h4 class="text-center">Thống kê lãi suất trong ngày</h4>
                                <GChart
                                type="ColumnChart"
                                :data="chartData"
                                :options="chartOptions"/>
                            </div>
                            <div class="col-12 col-sm-8 col-md-6 mb-4">
                                <h4 class="text-center">Thống kê nhập xuất trong ngày</h4>
                                <GChart
                                type="ColumnChart"
                                :data="chartData4"
                                :options="chartOptions4"/>
                            </div>
                       </div>
                       <hr>
                       <div class="row justify-content-center">
                           <div class="col-12 col-sm-8 col-md-6">
                                <h4 class="text-center">Thống kê nhập xuất trong tháng</h4>
                                <GChart
                                type="ColumnChart"
                                :data="chartData3"
                                :options="chartOptions3"/>
                           </div>
                           <div class="col-12 col-sm-8 col-md-6">
                                <h4 class="text-center">Thống kê lãi suất trong tháng</h4>
                                <GChart
                                type="LineChart"
                                :data="chartData2"
                                :options="chartOptions2"/>
                           </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { GChart } from 'vue-google-charts'
import datetime from 'vuejs-datetimepicker';
export default {
    name: 'statistic-main',
    components: {
        GChart,
        datetime
    },
    created() {
        this.val = this.currentDate
    },
    data(){
        return {
            chartData: [],
            chartData2: [],
            chartData3: [],
            chartData4: [],
            chartOptions: {
                    chart: {
                    title: 'Bảng thống kê lãi suất trong ngày',
                    subtitle: '',
                },
                height: 500,
            },
            chartOptions2: {
                    chart: {
                    title: 'Bảng thống kê lãi suất trong tháng',
                    subtitle: '',
                },
                height: 350,
            },
            chartOptions3: {
                    chart: {
                    title: 'Thống kê nhập xuất trong tháng',
                    subtitle: '',
                },
                height: 350,
            },
            chartOptions4: {
                    chart: {
                    title: 'Thống kê nhập xuất trong ngày',
                    subtitle: '',
                },
                height: 500,
            },
            val: null,
        }
    },
    methods: {
        fetchProfit(){
            axios.get('/api/statistics',{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                },
                params: {
                    date: this.val
                }
            })
            .then((response) => {
                this.chartData = response.data.loinhuantrongngay
                this.chartData2 = response.data.loinhuan1thang
                this.chartData3 = response.data.nhapxuat1thang
                this.chartData4 = response.data.nhapxuattrongngay
            })
            .catch((error) => {
                console.log(error)
            })
        },
    },
    watch: {
        val(){
            this.fetchProfit()
        }
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
        currentDate(){
            let myDate = new Date()
            var month = ('0' + (myDate.getMonth() + 1)).slice(-2);
            var date = ('0' + myDate.getDate()).slice(-2);
            var year = myDate.getFullYear();
            let currentDateWithFormat = date + '-' + month + '-' + year;
            return currentDateWithFormat
        }
    },
}
</script>
