<template>
    <div>
        <div class="text-center">
           <h4><i class="fas fa-user-cog"></i> Sửa thông tin khách hàng</h4>
       </div>
        <router-link  to="/admin/customers" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-clipboard-list"></i> Danh sách khách hàng</router-link>
        <template v-if="customer.name != null">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-md-8">
                    <div class="form-group">
                        <label for="CustomerName">Tên khách hàng:</label>
                        <input type="text" id="CustomerName" class="form-control"  v-model="customer.name">
                    </div>
                    <p><span v-if="errors.name" class="badge badge-danger">{{ errors.name[0] }}</span></p>
                    <div class="form-group">
                        <label for="CustomerEmail">Email: </label>
                        <input type="email" id="CustomerEmail" class="form-control" v-model="customer.email">
                    </div>
                    <p><span v-if="errors.email" class="badge badge-danger">{{ errors.email[0] }}</span></p>
                    <div class="form-group">
                        <label for="CustomerPhone">Số điện thoại: </label>
                        <input type="text" id="CustomerPhone" class="form-control" v-model="customer.phone">
                    </div>
                    <p><span v-if="errors.phone" class="badge badge-danger">{{ errors.phone[0] }}</span></p>
                    <div class="form-group">
                        <label for="CustomerAddress">Địa chỉ: </label>
                        <input type="text" id="CustomerAddress" class="form-control" v-model="customer.address">
                    </div>
                    <p><span v-if="errors.address" class="badge badge-danger">{{ errors.address[0]}}</span></p>
                    <div class="form-group">
                        <label for="TaxCode">Mã số thuế: </label>
                        <input type="text" id="TaxCode" class="form-control" v-model="customer.tax_code">
                    </div>
                    <div class="form-group mb-5">
                        <label for="Sorting">Chọn loại khách hàng</label>
                        <select class="form-control" id="Sorting" v-model="customer.sorting" >
                            <option value="khachhangle">- Khách hàng lẻ -</option>
                            <option value="khachhangsi">- Khách hàng sỉ -</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="alert">
                        <p class="text-center" v-html="alert">{{alert}}</p>
                    </div>
                    <div class="form-group mt-5">
                        <button class="btn btn-success form-control" @click="editCustomer">Lưu khách hàng</button>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="row justify-content-center">
                <h3>Không tìm thấy khách hàng</h3>
            </div>
        </template>
    </div>
</template>

<script>
export default {

    data(){
        return  {
            customer: {},
            errors: [],
            alert: null,
        }
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
    },
    created(){
        this.fetchCustomerbyId(this.$route.params.id)
    },
    methods: {
        fetchCustomerbyId(id){
            axios.get('/api/customers/'+id,{
                headers: {
                    'Authorization': 'Bearer '+this.currentUser.token,
                }
            })
            .then((response) => {
                this.customer = response.data
            })
            .catch((error) => {
                console.log(error)
            })
        },
        editCustomer(){
            this.errors = []
            this.alert = null
            axios.post('/api/customers/',this.customer,{
                headers: {
                    'Authorization': 'Bearer '+this.currentUser.token,
                }
            })
            .then((response) => {
                this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
            })
            .catch((error) =>{
                this.alert ='Lưu thất bại <i class="fas fa-times"></i>'
                if(error.response.status == 422){
                    this.errors = error.response.data.errors
                }
            })
        }
    }
}
</script>
