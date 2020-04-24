<template>
    <div>
        <div class="text-center">
           <h4><i class="fas fa-clipboard-list"></i> Danh sách hóa đơn</h4>
       </div>
        <router-link  to="/admin/orders/new" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-plus"></i> Thêm Hóa Đơn</router-link>
        <button class="btn btn-outline-warning btn-sm mb-2" @click="newList()"><i class="fa fa-refresh"></i> Làm mới danh sách</button>
       <div class="input-group mb-3">
            <input @keydown.enter="findOrder()" type="text" class="form-control" placeholder="Nhập khách hàng cần tìm hóa đơn..." v-model="ordersName">
            <div class="input-group-append">
                <select class="btn btn-warning" v-model="orderKind" @change="findOrder()">
                    <option value="tatcakhachhang" selected>- Tất cả hóa đơn -</option>
                    <option value="chuacokh" selected>- Chưa có KH -</option>
                    <option value="nhaphang">- Hóa đơn nhập hàng -</option>
                    <option value="banhang"> - Hóa đơn bán hàng -</option>
                </select>
                <button class="btn btn-success button-find" title="Tìm kiếm" @click="findOrder()"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <template v-if="!orders.length">
            <div class="row justify-content-center">
                <h3>Không có hóa đơn</h3>
            </div>
        </template>
        <template v-else>
        <table class="table table-bordered table-responsive text-white text-center">
            <thead>
                <th width="5%">#</th>
                <th width="20%">Tên khách hàng</th>
                <th width="20%">Địa chỉ</th>
                <th width="10%">Số điện thoại</th>
                <th width="10%">Mã số thuế</th>
                <th width="5%">Thuế</th>
                <th width="5%">Giảm giá</th>
                <th width="10%">Tổng giá trị</th>
                <th width="5%">Chi tiết HĐ</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xóa</th>
            </thead>
            <tbody>
                <tr v-for="(order,index) in orders" :key="order.id">
                    <th>{{parseInt(index += 1)}}</th>
                    <td>{{order.name}}</td>
                    <td>{{order.address}}</td>
                    <td>{{order.phone}}</td>
                    <td>{{order.tax_code}}</td>
                    <td>{{order.tax}}%</td>
                    <td>{{order.discount}}%</td>
                    <td>{{order.total_price | currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</td>
                    <td><button class="btn btn-info"  @click="viewOrderDetail(order)">Xem</button></td>
                    <td><router-link :to="{name: 'order-edit' , params: {id: order.id}}" class="btn btn-warning">Sửa</router-link></td>
                    <td><button class="btn btn-danger" @click="removeOrder(index,order.id)">Xóa</button></td>
                </tr>
            </tbody>
        </table>
        </template>
        <nav aria-label="...">
            <ul class="pagination mt-2 justify-content-center">
                <li v-show="firstpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2"  @click="findOrder(firstpageURL)"> First </a>
                </li>
                <li v-show="current_page" class="page-item">
                    <a  class="btn btn-outline-primary ml-2">{{current_page}} </a>
                </li>
                <li v-show="nextpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2" @click="findOrder(nextpageURL)"> Next </a>
                </li>
                <li v-show="prevpageURL" class="page-item">
                    <a  class="btn btn-outline-warning ml-2" @click="findOrder(prevpageURL)"> Back </a>
                </li>
                <li v-show="lastURL" class="page-item">
                    <a  class="btn btn-outline-danger ml-2" @click="findOrder(lastURL)"> Last </a>
                </li>
            </ul>
        </nav>
        <template  v-if="order_selected">
            <div class="row justify-content-center">
                <div class="col-md-8 bg-white text-dark" id="hoadon">
                    <div class="row">
                        <div class="col-3">

                        </div>
                        <div class="col-md-9">
                            <h4 class="mt-2" style="color: red; font-size: 13pt; font-weight: bold;">CÔNG TY TNHH THƯƠNG MẠI DỊCH VỤ XÂY DỰNG TUẤN MINH TECH</h4>
                            <span>Mã số thuế: <span class="font-weight-bold">0 3 1 3 3 0 7 3 3 5</span></span><br>
                            <span>Địa chỉ: 127/93/15 Âu Cơ, Phường 14, Quận 11, Thành phố Hồ Chí Minh</span><br>
                            <div class="row">
                                <div class="col">
                                    <span>Điện thoại: </span>
                                </div>
                                <div class="col">
                                    <span>Email: </span>
                                </div>
                            </div>
                            <span>Số tài khoản: 0441000690847 Ngân hàng Vietcombank - Chi nhánh Tân Bình</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">

                        </div>
                        <div class="col-4 text-center">
                            <span style="font-weight: bold; font-size: 15pt; color: red;">HÓA ĐƠN</span><br>
                            <span style="font-weight: bold; font-size: 15pt; color: red;">GIÁ TRỊ GIA TĂNG</span><br>
                            <span>Liên 2: Giao cho người mua</span><br>
                            <span class="font-italic">Ngày  {{order_selected.created_at | moment("d")}}  tháng  {{order_selected.created_at | moment("MM")}}  năm  {{order_selected.created_at | moment("YYYY")}}</span>
                        </div>
                        <div class="col-4 mt-2">
                            <span class="ml-5">Mẫu số : <span class="font-weight-bold">01GTKT3/003</span></span><br>
                            <span class="ml-5">Ký hiệu : <span class="font-weight-bold">TM/018P</span></span><br>
                            <span class="ml-5">Số : <span class="font-weight-bold" style="color: red; font-size: 13pt;">00000740</span></span><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <p class="font-weight-bold">Họ tên người mua hàng: {{order_selected.name}}</p>
                        <p class="font-weight-bold">Tên đơn vị: </p>
                        <p class="font-weight-bold">Mã số thuế: {{order_selected.tax_code}}</p>
                        <p class="font-weight-bold">Địa chỉ: {{order_selected.address}}</p>
                        <div class="row">
                            <div class="col-4">
                                <p class="font-weight-bold">Hình thức thanh toán: </p>
                            </div>
                            <div class="col-8">
                                <p class="font-weight-bold">Số tài khoản:</p>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered text-dark text-center">
                        <thead>
                            <td  width="5%">STT</td>
                            <th  width="30%">Tên mặt hàng</th>
                            <th  width="10%">Đơn vị</th>
                            <th  width="10%">Số lượng</th>
                            <th  width="20%">Đơn giá</th>
                            <th  width="10%">Giảm giá</th>
                            <th  width="15%">Tổng cộng</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th height="50">1</th>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7 = (4 x 5) - (4 x 5) x (6 / 100)</td>
                            </tr>
                            <tr v-for="(item,index) in order_selected.order_details" :key="item.id">
                                <th height="50">{{ index += 1 }}</th>
                                <td>{{item.product_name}}</td>
                                <td>{{item.unit}}</td>
                                <td>{{item.quantity}}</td>
                                <td>{{item.price| currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false})}}</td>
                                <td>{{item.discount}} %</td>
                                <td class="text-right">{{  (item.quantity * item.price) - (item.quantity * item.price)*(item.discount / 100) | currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-left">
                                    <div class="row">
                                        <div class="col align-self-center text-center">
                                            <p class="font-weight-bold ">Thuế GTGT: {{order_selected.tax}}%</p>
                                        </div>
                                        <div class="col">
                                            <p class="font-weight-bold">Tổng tiền hàng: </p>
                                            <p class="font-weight-bold">Giảm giá thêm: {{order_selected.discount}}%</p>
                                            <p class="font-weight-bold">Tiền thuế: </p>
                                            <p class="font-weight-bold">Tổng tiền hàng sau thuế: </p>
                                        </div>
                                        <div class="col text-right">
                                            <p class="font-weight-bold">{{ totalPrice | currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</p>
                                            <p class="font-weight-bold">{{ totalPrice * (order_selected.discount / 100) | currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</p>
                                            <p class="font-weight-bold">{{ totalPrice * (order_selected.tax / 100)   | currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</p>
                                            <p class="font-weight-bold">{{ order_selected.total_price | currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-left">
                                    <p class="font-weight-bold">Số tiền viết bằng chữ:</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="row mb-5">
                        <div class="col">
                            <p class="font-weight-bold text-center">Người mua hàng</p>
                            <p class="font-italic text-center" style="font-size: 12px;">(Ký, ghi rõ họ tên)</p>
                        </div>
                        <div class="col">
                            <p class="font-weight-bold text-center">Người bán hàng</p>
                            <p class="font-italic text-center" style="font-size: 12px;">(Ký, ghi rõ họ tên)</p>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="form-group mt-3 text-right">
                        <a class="btn btn-success" @click="printOrder()"><i class="fa fa-print" aria-hidden="true"></i> In hóa đơn</a>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    name: 'list',
    data(){
        return {
            orders: [],
            order_selected: null,
            next_page_url: null,
            prev_page_url: null,
            last_page_url: null,
            first_page_url: null,
            current_page: 1,
            ordersName: null,
            orderKind: 'tatcakhachhang',
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
        },
        totalPrice() {
            return this.order_selected.order_details.reduce((total,item) => {
                return total + item.totalprice
            },0)
        }
    },
    created(){
        this.findOrder()
    },
    methods: {
        removeOrder(index,id){
            if(confirm('Bạn có muốn xóa hóa đơn này?')){
                this.order_selected = null,
                this.orders.splice(index,1)
                axios.post('/api/orders/remove',{
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

        findOrder(page_url){
            if(this.orderKind === 'chuacokh'){
                this.ordersName = ''
            }
            page_url = page_url || 'http://localhost:8000/api/orders/find'
            axios.post(page_url,
                {
                    name: this.ordersName,
                    kind: this.orderKind
                },
                {
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                    .then((res) => {
                        this.orders = res.data.data
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
            this.orderKind = 'tatcakhachhang'
            this.ordersName = null
            this.findOrder()
        },
        viewOrderDetail(order){
            this.order_selected = order
            console.log(order)
        },
        printOrder(){
            this.$htmlToPaper('hoadon');
        }
    }
}
</script>

<style scoped>
.button-find{
    width: 150px;
}
.order-bottom {
    height: 200px;
}
</style>
