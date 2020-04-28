<template>
    <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-default">
                        <div class="card-header bg-success text-white text-center"><h4>Thống kê</h4></div>
                        <div class="card-body bg-dark text-white">
                            <div class="row text-dark mb-2">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div class="form-inline">
                                            <label for="Time" class="text-white mr-2">Ngày:</label>
                                            <datetime format="DD-MM-YYYY" v-model="val" id="Time"></datetime>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div class="form-inline">
                                            <label for="Time1" class="text-white mr-2">Từ ngày:</label>
                                            <datetime format="DD-MM-YYYY" v-model="from_date" id="Time1"></datetime>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div class="form-inline">
                                            <label for="Time2" class="text-white mr-2">Đến ngày:</label>
                                            <datetime format="DD-MM-YYYY" v-model="to_date" id="Time2"></datetime>
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
                            <div class="row mt-2">
                                <div class="col-12 col-md-12 col-lg-4 col-xl-3 bg-dark">
                                    <b-card title="Thống kê nhập xuất" text-variant="dark"  style="height: 400px; overflow: scroll;">
                                        <b-card-text>
                                            <b-form @reset="onReset" @keydown.enter.stop.prevent>
                                                <b-form-group>
                                                    <input type="text"
                                                        id="input-1"
                                                        :value="searchQuery"
                                                        @change="searchQuery = $event.target.value"
                                                        @keydown.enter="searchQuery = $event.target.value"
                                                        class="form-control mt-2"
                                                        placeholder="Nhập tên mặt hàng cần tìm"
                                                    >
                                                    <b-button type="reset" class="mt-2" variant="danger" v-if="searchQuery !== ''">Làm mới danh sách</b-button>
                                                </b-form-group>
                                            </b-form>
                                            <b-form-group>
                                                <template v-slot:label>
                                                    <b v-if="resultQuery !== null && resultQuery.length > 0">Chọn các sản phẩm: </b>
                                                    <b v-else>Không tìm thấy</b>
                                                    <br>
                                                    <b-form-checkbox
                                                        v-if="searchQuery === ''"
                                                        v-model="allSelected"
                                                        :indeterminate="indeterminate"
                                                        aria-describedby="options"
                                                        aria-controls="options"
                                                        @change="toggleAll">
                                                    {{ allSelected ? 'Bỏ chọn tất cả' : 'Chọn tất cả' }}
                                                    </b-form-checkbox>
                                                    <b-form-checkbox
                                                        v-else
                                                        v-model="allSelected2"
                                                        :indeterminate="indeterminate2"
                                                         aria-describedby="options"
                                                        aria-controls="options"
                                                        @change="toggleAll2">
                                                    Chọn tất cả theo tìm kiếm:  <span class="font-weight-bold">{{searchQuery}}</span>
                                                    </b-form-checkbox>
                                                </template>
                                                <b-form-checkbox-group
                                                    id="options"
                                                    v-model="selected"
                                                    :options="resultQuery"
                                                    name="options"
                                                    class="ml-4"
                                                    aria-label="Individual options"
                                                    stacked
                                                ></b-form-checkbox-group>
                                            </b-form-group>
                                        </b-card-text>
                                    </b-card>
                                </div>
                                <div class="col-12 col-md-12 col-lg-8 col-xl-9 bg-dark">
                                   <b-card text-variant="dark" style="height: 400px; overflow:scroll;">
                                           <div class="row">
                                               <div class="col-12 col-md-12 col-lg-12 col-xl-6">
                                                   <b-card title="Danh sách các sản phẩm đã chọn thống kê" text-variant="dark" style="height: 350px;overflow: scroll;">
                                                        <button :disabled="selected.length === 0" class="btn btn-success mb-2" @click="handleStatisticProduct()">Thống kê</button>
                                                        <ul class="list-group">
                                                            <li v-for="(item,index) in selected"
                                                                    class="list-group-item"
                                                                    :key="item+Math.random()"
                                                                    >{{item}}
                                                                    <button
                                                                        class="float-right btn btn-danger"
                                                                        @click.self="selected.splice(index,1)">X
                                                                    </button>
                                                            </li>
                                                        </ul>
                                                    </b-card>
                                               </div>
                                               <div class="col-12 col-md-12 col-lg-12 col-xl-6">
                                                   <b-card title="abc" text-variant="dark" style="height: 350px;overflow: scroll;">
                                                       <table class="table table-dark table-responsive table-bordered">
                                                           <thead>
                                                               <th></th>
                                                           </thead>
                                                           <tbody>
                                                               <tr>
                                                                   <td>
                                                                       {{selected_finish}}
                                                                   </td>
                                                               </tr>
                                                           </tbody>
                                                       </table>
                                                    </b-card>
                                               </div>
                                           </div>
                                    </b-card>
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
        this.fetchProducts()
    },
    data(){
        return {
            loading: false,
            searchQuery: '',
            options: [],
            selected: [],
            selected_finish: [],
            allSelected: false,
            indeterminate: false,
            indeterminate2: false,
            allSelected2: false,
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
        toggleAll(checked) {
             this.selected = checked ? this.options.slice() : []
        },
        toggleAll2(checked){
            if(checked){
                let vm = this
                this.resultQuery.forEach((item) => {
                    const selectItem = this.selected.find(select_item => select_item === item)
                    if(!selectItem){
                        this.selected.push(item)
                    }
                })
            }else{
                let vm = this
                this.resultQuery.forEach((item) => {
                    const selectItem = this.selected.findIndex(select_item => select_item === item)
                    if(selectItem !== -1){
                        this.selected.splice(selectItem,1)
                    }
                })
            }
        },
        fetchProducts(){
            axios.get('/api/products/all',{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            }).then((res) => {
                res.data.forEach((item,index) => {
                    if(index < 999){
                        this.options.push(item.name)
                    }
                })
            }).catch((err) => {
                console.log(err)
            })
        },
        onSubmit(evt) {
            evt.preventDefault()
            alert(JSON.stringify(this.form))
        },
        onReset(evt) {
            evt.preventDefault()
            // Reset our form values
            this.searchQuery = ''
            // Trick to reset/clear native browser form validation state
            this.show = false
            this.$nextTick(() => {
                this.show = true
            })
        },
        handleStatisticProduct(){
            this.loading = true
            axios.post('/api/statistics/products',{
                selected: JSON.stringify(this.selected),
                from_date: this.from_date,
                to_date: this.to_date
            },{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            }).then((res) => {
                this.selected_finish = res.data
                this.loading = false
            }).catch((err) => {
                console.log(err)
                this.loading = false
            })
        }
    },
    watch: {
        val(){
            this.fetchProfit()
        },
        from_date(){
              this.fetchProfit()
        },
        to_date(){
              this.fetchProfit()
        },
        selected(newVal, oldVal) {
        // Handle changes in individual flavour checkboxes
            if (newVal.length === 0) {
            this.indeterminate = false
            this.allSelected = false
            } else if (newVal.length === this.options.length) {
            this.indeterminate = false
            this.allSelected = true
            } else {
            this.indeterminate = true
            this.allSelected = false
            }
        },
        resultQuery(){
            this.allSelected2 = false
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
        },
        resultQuery(){
            if(this.searchQuery){
            return this.options.filter((item)=>{
                return this.searchQuery.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().split(' ').every(v => item.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().includes(v))
            })
            }else{
                return this.options;
            }
        },
    },

}
</script>
