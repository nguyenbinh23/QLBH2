<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header bg-success text-white text-center"><h4>Thống kê</h4></div>
                    <div class="card-body bg-dark text-white">
                       <div class="row text-dark mb-2">
                           <div class="col-4 col-md-4">
                              <div class="form-group">
                                  <div class="form-inline">
                                    <label for="Time" class="text-white mr-2">Ngày:</label>
                                    <datetime format="DD-MM-YYYY" v-model="val" id="Time"></datetime>
                                  </div>
                              </div>
                            </div>
                            <div class="col-4 col-md-4">
                              <div class="form-group">
                                  <div class="form-inline">
                                    <label for="Time" class="text-white mr-2">Từ ngày:</label>
                                    <datetime format="DD-MM-YYYY" v-model="from_date" id="Time"></datetime>
                                  </div>
                              </div>
                            </div>
                            <div class="col-4 col-md-4">
                              <div class="form-group">
                                  <div class="form-inline">
                                    <label for="Time" class="text-white mr-2">Đến ngày:</label>
                                    <datetime format="DD-MM-YYYY" v-model="to_date" id="Time"></datetime>
                                  </div>
                              </div>
                            </div>
                       </div>
                       <div class="row justify-content-center">
                            <div class="col-12 col-sm-8 col-md-6 mb-4">
                                <h4 class="text-center">Thống kê nhập xuất trong ngày</h4>
                                <GChart
                                type="ColumnChart"
                                :data="chartData4"
                                :options="chartOptions4"/>
                            </div>
                            <div class="col-12 col-sm-8 col-md-6 mb-4">
                                <h4 class="text-center">Thống kê nhập xuất từ {{from_date}} đến {{to_date}}</h4>
                                <GChart
                                type="ColumnChart"
                                :data="chartData2"
                                :options="chartOptions2"/>
                            </div>
                       </div>
                       <hr>
                       <div class="row justify-content-center">
                           <div class="col-12">
                                <h4 class="text-center">Thống kê nhập xuất trong tháng</h4>
                                <GChart
                                type="ColumnChart"
                                :data="chartData3"
                                :options="chartOptions3"/>
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
        this.from_date = this.fromDate
        this.to_date = this.currentDate
    },
    data(){
        return {
            chartData2: [],
            chartOptions2: {
                    chart: {
                    title: `Thống kê nhập xuất từ ${this.from_date} đến ${this.to_date} ngày`,
                    subtitle: '',
                },
                height: 500,
            },
            chartData3: [],
            chartData4: [],
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
            from_date: null,
            to_date: null,
        }
    },
    methods: {
        fetchProfit(){
            axios.get('/api/statistics',{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                },
                params: {
                    date: this.val,
                    from_date: this.from_date,
                    to_date: this.to_date
                }
            })
            .then((response) => {
                this.chartData2 = response.data.nhapxuattrongkhoangthoigian
                this.chartData3 = response.data.nhapxuat1thang
                this.chartData4 = response.data.nhapxuattrongngay
            })
            .catch((error) => {
                this.$Progress.fail();
            })
        },
    },
    watch: {
        val(){
            this.fetchProfit()
        },
        from_date(newVal , oldVal){
            if( (new Date(this.from_date).getDate() > new Date(this.to_date).getDate()))
            {
                this.from_date = oldVal
            }else {
                this.from_date = newVal
                this.fetchProfit()
            }
        },
        to_date(){
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
        },
        fromDate(){
            let myDate = new Date()
            var month = ('0' + (myDate.getMonth())).slice(-2);
            var date = ('0' + myDate.getDate()).slice(-2);
            var year = myDate.getFullYear();
            let currentDateWithFormat = date + '-' + month + '-' + year;
            return currentDateWithFormat
        }
    },
}
</script>
