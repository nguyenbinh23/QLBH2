<template>
    <div>
        <div class="text-center">
           <h4><i class="fas fa-clipboard-list"></i> Danh sách khách hàng</h4>
       </div>
        <router-link  to="/admin/customers/new" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-user-plus"></i> Thêm Khách Hàng</router-link>
        <button class="btn btn-outline-warning btn-sm mb-2" @click="newList()"><i class="fa fa-sync-alt"></i> Làm mới danh sách</button>
       <div class="input-group mb-3">
            <input @keydown.enter="findCustomer()" type="text" class="form-control" placeholder="Nhập khách hàng cần tìm..." v-model="customer_name_find">
            <div class="input-group-append">
                <select class="btn btn-warning" v-model="customer_sorting" @change="findCustomer()">
                    <option value="tatcakhachhang" selected>- Tất cả khách hàng -</option>
                    <option value="khachhangle">- Khách hàng lẻ -</option>
                    <option value="khachhangsi"> - Khách hàng sỉ -</option>
                </select>
                <button class="btn btn-success button-find" title="Tìm kiếm" @click="findCustomer()"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <template v-if="!customers.length">
            <div class="row justify-content-center">
                <h3>Không có khách hàng</h3>
            </div>
        </template>
        <template v-else>
        <table class="table table-bordered table-responsive text-white text-center">
            <thead>
                <th width="5%">#</th>
                <th width="20%">Tên khách hàng</th>
                <th width="20%">Địa chỉ</th>
                <th width="20%">Email</th>
                <th width="10%">Phone</th>
                <th width="15%">Mã số thuế</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xóa</th>
            </thead>
             <transition-group tag="tbody" name="view">
                <tr v-for="(customer,index) in customers" :key="customer.id">
                    <th>{{parseInt(index + 1)}}</th>
                    <td>{{customer.name}}</td>
                    <td>{{customer.address}}</td>
                    <td>{{customer.email}}</td>
                    <td>{{customer.phone}}</td>
                    <td>{{customer.tax_code}}</td>
                    <td><router-link :to="{name: 'customer-edit' , params: {id: customer.id}}" class="btn btn-warning">Sửa</router-link></td>
                    <td><button class="btn btn-danger" @click="removeCustomer(index,customer.id)">Xóa</button></td>
                </tr>
             </transition-group>
        </table>
        </template>
        <nav aria-label="...">
            <ul class="pagination mt-2 justify-content-center">
                <li v-show="firstpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2"  @click="findCustomer(firstpageURL)"> First </a>
                </li>
                <li v-show="current_page" class="page-item">
                    <a  class="btn btn-outline-primary ml-2">{{current_page}} </a>
                </li>
                <li v-show="nextpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2" @click="findCustomer(nextpageURL)"> Next </a>
                </li>
                <li v-show="prevpageURL" class="page-item">
                    <a  class="btn btn-outline-warning ml-2" @click="findCustomer(prevpageURL)"> Back </a>
                </li>
                <li v-show="lastURL" class="page-item">
                    <a  class="btn btn-outline-danger ml-2" @click="findCustomer(lastURL)"> Last </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'list',
    data(){
        return {
            customers: [],
            next_page_url: null,
            prev_page_url: null,
            last_page_url: null,
            first_page_url: null,
            current_page: 1,
            customer_name_find: null,
            customer_sorting: 'tatcakhachhang',
            customersFind: []
        }
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
        firstpageURL(){
            return this.first_page_url
        },
        nextpageURL(){
            return this.next_page_url
        },
        prevpageURL(){
            return this.prev_page_url
        },
        lastURL(){
            return this.last_page_url
        }
    },
    created(){
        this.findCustomer()
    },
    methods: {
        removeCustomer(index,id){
            if(confirm('Bạn có muốn xóa khách hàng này')){
                this.customers.splice(index,1)
                axios.post('/api/customers/remove',{
                    id: id
                },
                {
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                    .then((res) => {
                        alert('Xóa thành công')
                    })
                    .catch((error) => {
                        alert('Xóa thất bại')
                    })
            }
        },

        findCustomer(page_url){
            page_url = page_url || 'http://localhost:8000/api/customers/find'
            axios.post(page_url,
                {
                    name: this.customer_name_find,
                    sorting: this.customer_sorting
                },
                {
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                    .then((res) => {
                        this.customers = res.data.data
                        this.next_page_url = res.data.next_page_url
                        this.prev_page_url = res.data.prev_page_url
                        this.last_page_url = res.data.last_page_url
                        this.first_page_url = res.data.first_page_url
                        this.current_page = res.data.current_page
                    })
                    .catch((error) => {
                        console.log(error)
                    })
        },
        newList(){
            this.customer_sorting = 'tatcakhachhang'
            this.customer_name_find = null
            this.findCustomer()
        }
    }
}
</script>

<style scoped>
.button-find{
    width: 150px;
}
</style>
