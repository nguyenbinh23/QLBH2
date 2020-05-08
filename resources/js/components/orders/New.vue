<template>
    <div>
        <!-- Hàng hóa -->
        <div class="text-center">
           <h4><i class="fas fa-clipboard-list"></i> Danh sách hàng hóa</h4>
       </div>
        <router-link  to="/admin/orders" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-clipboard-list"></i> Danh sách hóa đơn</router-link>
        <button class="btn btn-outline-warning btn-sm mb-2" @click="newList()"><i class="fa fa-sync-alt"></i> Làm mới danh sách</button>
       <div class="input-group mb-3">
            <input @keydown.enter="findProduct()" type="text" class="form-control" placeholder="Nhập hàng hóa cần tìm..." v-model="product_name_find">
            <div class="input-group-append">
                <select class="btn btn-warning" v-model="product_category" @change="findProduct()">
                    <option value="tatcatheloai" selected>- Tất cả hàng hóa -</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">- {{category.category_name}} -</option>
                </select>
                <button class="btn btn-success button-find" title="Tìm kiếm" @click="findProduct()"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <template v-if="products && products.length === 0">
            <div class="row justify-content-center">
                <h3>Không có hàng hóa</h3>
            </div>
        </template>

        <template name="view">
            <table class="table table-bordered table-responsive text-white text-center">
                <thead>
                    <th width="10%">Hình ảnh</th>
                    <th width="10%">Mã hàng hóa</th>
                    <th width="30%">Tên hàng hóa</th>
                    <th width="10%">Số lượng</th>
                    <th width="10%">Đơn vị</th>
                    <th width="20%">Bảng giá</th>
                    <th width="10%">Thêm</th>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <th><img :src="'/storage/cover_images/'+product.image" style="width: 50px;height: 50px;"></th>
                        <td>{{product.code}}</td>
                        <td>{{product.name}}</td>
                        <template v-if="product.quantity < 5">
                            <td><span class="btn btn-outline-danger">Số lượng còn ít: {{product.quantity}}</span></td>
                        </template>
                        <template v-else>
                            <td>{{product.quantity}}</td>
                        </template>
                        <td>{{product.unit}}</td>
                        <td>
                            <div class="form-group">
                                <select class="form-control" v-model="product.price">
                                    <option
                                    v-for="price_type in product.pricelist" :key="price_type.id"
                                    :selected="price_type.name === 'Giá bán lẻ'"
                                    :value="price_type.cost">
                                    {{price_type.name}}: {{price_type.cost | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</option>
                                </select>
                            </div>
                        </td>
                    <td><button v-if="addButton" @click="addProductToCart(product)" class="btn btn-outline-primary"><i class="fa fa-cart-plus"></i>  Thêm vào HĐ</button></td>
                    </tr>
                </tbody>
            </table>
        </template>
        <nav aria-label="...">
            <ul class="pagination mt-2 justify-content-center">
                <li v-show="firstpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2"  @click="findProduct(firstpageURL)"> First </a>
                </li>
                <li v-show="current_page" class="page-item">
                    <a  class="btn btn-outline-primary ml-2">{{current_page}} </a>
                </li>
                <li v-show="nextpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2" @click="findProduct(nextpageURL)"> Next </a>
                </li>
                <li v-show="prevpageURL" class="page-item">
                    <a  class="btn btn-outline-warning ml-2" @click="findProduct(prevpageURL)"> Back </a>
                </li>
                <li v-show="lastURL" class="page-item">
                    <a  class="btn btn-outline-danger ml-2" @click="findProduct(lastURL)"> Last </a>
                </li>
            </ul>
        </nav>
        <hr>
        <div class="text-center">
           <h4><i class="fas fa-file-invoice"></i> Lập hóa đơn</h4>
        </div>
        <table class="table table-responsive table-bordered text-white text-center order">
            <thead>
                <th scope="col" width="5%">Hình ảnh</th>
                <th scope="col" width="5%">Mã hàng hóa</th>
                <th scope="col" width="15%">Tên hàng hóa</th>
                <th scope="col" width="5%">Đơn vị</th>
                <th scope="col" width="20%">Giá</th>
                <th scope="col" width="10%">Số lượng</th>
                <th scope="col" width="10%">Giảm giá %</th>
                <th scope="col" width="20%">Tổng</th>
                <th scope="col" width="10%">Thao tác</th>
            </thead>
                <transition-group name="view" tag="tbody">
                    <tr scope="row" v-for="(product,index) in carts" :key="product.id">
                        <th><img :src="'/storage/cover_images/'+product.image" style="width: 50px;height: 50px;"></th>
                        <td>{{product.code}}</td>
                        <td>{{product.name}}</td>
                        <td>{{product.unit}}</td>
                        <td v-if="activeIndexPrice === index">
                            <div class="form-group">
                                <p>Giá hiện tại: {{ product.price | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false})}}</p>
                                <p v-if="tempPrice">Giá mới: {{ tempPrice | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false})}}</p>
                                <input type="text" class="form-control" v-model="tempPrice">
                                <button class="btn btn-success form-control mt-2" @click="changeCartItemPrice(product)">Thay đổi</button>
                                <button class="btn btn-danger form-control mt-2" @click="unsetActiveIndexPrice()">Đóng</button>
                            </div>
                        </td>
                        <td class="price-item" v-else @click="setActiveIndexPrice(index,product)">{{product.price | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</td>
                        <td>
                            <div class="form-group">
                                <input type="number" min="0" class="form-control"
                                v-model="product.quantity"
                                @input="changeQuantity(product)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" min="0" max="100" class="form-control"
                                v-model="product.discount"
                                @input="changeQuantity(product)">
                            </div>
                        </td>
                        <td>
                            {{product.totalprice | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}
                        </td>
                        <td><button @click="removeProductfromCart(index)" class="btn btn-outline-warning"><i class="fa fa-minus"></i> Xóa</button></td>
                    </tr>
                </transition-group>
        </table>
        <div class="row">
           <div class="col text-right">
                <button class="btn btn-outline-danger" @click="removeAllProductfromCart()"><i class="fa fa-trash"></i> Xóa hết</button>
           </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <h4><span class="badge badge-warning">Có {{carts.length}} mặt hàng trong hóa đơn</span></h4>
            </div>
            <div class="col">
                <h4 class="text-right"><span class="badge badge-danger">Tổng: {{totalPrice | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</span></h4>
            </div>
        </div>
        <hr>
        <div class="text-center">
           <h4><i class="fas fa-pen"></i> Thông tin hóa đơn và khách hàng</h4>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <h5 for="">Chọn loại hóa đơn:</h5>
                </div>
                <div class="form-group">
                     <div class="custom-control custom-radio custom-control-inline text-center">
                        <input type="radio" class="custom-control-input" checked id="defaultInline2" name="inlineDefaultRadiosExample" v-model="kind" value="banhang">
                        <label class="custom-control-label" for="defaultInline2">Hóa Đơn Bán Hàng</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline text-center">
                        <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample" v-model="kind" value="nhaphang">
                        <label class="custom-control-label" for="defaultInline1">Hóa Đơn Nhập Hàng</label>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <h5>Thông tin khách hàng: </h5>
                </div>
                <div class="form-group">
                    <label for="SearchQuery">Nhập tên khách hàng: </label>
                    <input id="SearchQuery" class="form-control" autocomplete="off" placeholder="Có thể nhập email hoặc phone để tìm" type="text"
                    v-model="searchQuery"
                    @focus="modal = true" @input="removeSelectedCustomer()">
                    <div v-if="resultQuery && modal">
                        <ul class="results">
                            <li class="results-item"
                            v-for="result in resultQuery"
                            :key="result.id"
                            @click="setCustomer(result)">Tên: {{result.name}} <span v-if="result.phone">- SĐT: {{result.phone}} </span><span v-if="result.address">- Địa chỉ: {{result.address}} </span></li>
                        </ul>
                        <ul class="results-close text-right" @click="closeModal" v-if="resultQuery.length > 0"><button class="btn btn-danger"> X </button></ul>
                    </div>
                </div>
                <template class="form-group" v-if="customer.name === ''">
                    <small>Chưa có khách hàng</small>
                    <br>
                    <input type="radio" id="Radio1" name="" value="themmoi" v-model="addCustomer">
                    <label for="CustomerRadio">Thêm mới khách hàng</label>
                    <input type="radio" id="Radio2" class="ml-2" name="CustomerRadio" checked value="khongthem" v-model="addCustomer">
                    <label for="Radio2">Không thêm khách hàng</label>
                </template>
                <template v-if="modal == false && customer.name !== ''">
                    <h5>Thông tin khách hàng đang chọn: </h5>
                    <table class="table table-responsive table-bordered bg-white text">
                        <thead>
                            <th scope="col" width="30%">Tên KH</th>
                            <th scope="col" width="10%">Email KH</th>
                            <th scope="col" width="30%">Địa chỉ</th>
                            <th scope="col" width="10%">Phone</th>
                            <th scope="col" width="10%">Mã số thuế</th>
                            <th scope="col" width="10%">Loại KH</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{customer.name}}</td>
                                <td>{{customer.email}}</td>
                                <td>{{customer.address}}</td>
                                <td>{{customer.phone}}</td>
                                <td>{{customer.tax_code}}</td>
                                <td>
                                    <template v-if="customer.sorting === 'khachhangle'">Khách hàng lẻ</template>
                                    <template v-else>Khách hàng sỉ</template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <div class="form-group">
                    <label for="TaxCode">Mã số thuế (Có thể nhập mã khác): </label>
                    <input for="TaxCode" class="form-control" type="text" v-model="tax_code">
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <label for="Tax">Thuế hóa đơn %:  </label>
                            <input type="number" class="form-control" id="Tax" min="0" max="100" v-model="tax">
                        </div>
                        <div class="col-6 col-md-6">
                            <label for="Discount">Giảm giá %:  </label>
                            <input type="number" class="form-control" id="Discount" min="0" max="100" v-model="discount">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <h5 class="text-right">Tổng giá trị hóa đơn (Bao gồm VAT và DISCOUNT): </h5><h3 class="text-right"><span class="badge badge-danger">{{totalpriceAfterTaxAndDiscount | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false})  }}</span></h3>
                </div>
                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html=" alert">{{alert}}</p>
                </div>
                <div class="form-group">
                    <a class="form-control btn btn-success" @click="createOrder()"><i class="fa fa-print" aria-hidden="true"></i> Lập hóa đơn</a>
                </div>
            </div>
        </div>
        <template  v-if="order_success && order_success.kind === 'banhang'">
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
                            <span class="font-italic">Ngày  {{order_success.created_at | moment("d")}}  tháng  {{order_success.created_at | moment("MM")}}  năm  {{order_success.created_at | moment("YYYY")}}</span>
                        </div>
                        <div class="col-4 mt-2">
                            <span class="ml-5">Mẫu số : <span class="font-weight-bold">01GTKT3/003</span></span><br>
                            <span class="ml-5">Ký hiệu : <span class="font-weight-bold">TM/018P</span></span><br>
                            <span class="ml-5">Số : <span class="font-weight-bold" style="color: red; font-size: 13pt;">00000740</span></span><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <p class="font-weight-bold">Họ tên người mua hàng: {{order_success.name}}</p>
                        <p class="font-weight-bold">Tên đơn vị: </p>
                        <p class="font-weight-bold">Mã số thuế: {{order_success.tax_code}}</p>
                        <p class="font-weight-bold">Địa chỉ: {{order_success.address}}</p>
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
                            <tr v-for="(item,index) in order_success.order_details" :key="item.id">
                                <th height="50">{{ index += 1 }}</th>
                                <td>{{item.product_name}}</td>
                                <td>{{item.unit}}</td>
                                <td>{{item.quantity}}</td>
                                <td>{{item.price | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false})}}</td>
                                <td>{{item.discount}} %</td>
                                <td class="text-right">{{ item.totalprice | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</td>
                            </tr>

                            <tr>
                                <td colspan="7" class="text-left">
                                    <div class="row">
                                        <div class="col align-self-center text-center">
                                            <p class="font-weight-bold ">Thuế GTGT: {{order_success.tax}}%</p>
                                        </div>
                                        <div class="col">
                                            <p class="font-weight-bold">Tổng tiền hàng: </p>
                                            <p class="font-weight-bold">Giảm giá thêm: {{order_success.discount}}%</p>
                                            <p class="font-weight-bold">Tiền thuế: </p>
                                            <p class="font-weight-bold">Tổng tiền hàng sau thuế: </p>
                                        </div>
                                        <div class="col text-right">
                                            <p class="font-weight-bold">{{totalPriceOrderSuccess | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</p>
                                            <p class="font-weight-bold">{{totalPriceOrderSuccess * ( order_success.discount / 100) | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</p>
                                            <p class="font-weight-bold">{{taxOrderSuccess | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false})}}</p>
                                            <p class="font-weight-bold">{{totalPriceAfterTaxOrderSuccess | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false})}}</p>
                                        </div>
                                    </div>
                                   <p class="font-weight-bold" style="border-top: 1px solid #dee2e6; padding-top: 20px;">Số tiền viết bằng chữ: <span class="font-weight-light">{{DocTienBangChu(totalPriceAfterTaxOrderSuccess)}}</span></p>
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
            //product & category & customer
            tempPrice: null,
            activeIndexPrice: null,
            customers: [],
            customer: {
                id: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                tax_code: '',
                sorting: '',
            },
            tax_code: '',
            products: [],
            categories: [],
            next_page_url: null,
            prev_page_url: null,
            last_page_url: null,
            first_page_url: null,
            current_page: 1,
            product_name_find: null,
            product_category: 'tatcatheloai',
            productsFind: [],
            //cart
            carts: [],
            selected_price: '',
            badge: 0,
            totalprice: 0,
            addButton: true,

            //order
            addCustomer: "khongthem",
            kind: 'banhang',
            tax: 10,
            discount: 0,
            searchQuery: '',
            order_success: null,
            totalPriceOrderSuccess: null,
            totalPriceAfterTaxOrderSuccess: null,
            taxOrderSuccess: null,


            //
            alert: null,
            errors: {},
            modal: false,
            success: false
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
        totalPrice(){
            return this.carts.reduce((total, item) => {
                return total + item.totalprice
            }, 0)
        },
        totalpriceAfterTaxAndDiscount(){
            let temp = this.totalPrice - this.totalPrice * (this.discount / 100)
            return (temp + (temp * this.tax / 100))
        },
        resultQuery(){
            if(this.searchQuery){
            return this.customers.filter((item)=>{
                return this.searchQuery.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().split(' ').every(v => item.name.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().includes(v))
            })
            }else{
                return this.customers;
            }
        }

    },
    created(){
        this.fetchCategories()
        this.fetchCustomers()
        this.findProduct()
    },
    watch: {
        customerRadio() {
            this.discount = 0
            this.tax = 10
            this.new_customer = {}
        },
        discount(){
            if(this.discount < 0){
                this.discount = 0
            }
            if(this.discount > 100){
                this.discount = 100
            }
        },
        tax(){
            if(this.tax < 0){
                this.tax = 0
            }
            if(this.tax > 100){
                this.tax = 100
            }
        },
        kind(){
            this.carts = []
            alert('Bạn vừa đổi loại hóa đơn, vui lòng thêm lại hàng hóa')
        }
    },
    methods: {
        fetchCustomers(){
            axios.get('/api/customers/all',{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((res) => {
                this.customers = res.data
            })
            .catch(() => {
                console.log('Lấy khách hàng thất bại!!!')
            })
        },
        fetchCategories(){
            axios.get('/api/categories/all',{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((res) => {
                this.categories = res.data
            })
            .catch(() => {
                console.log('Lấy thể loại thất bại!!!')
            })
        },
        findProduct(page_url){
            page_url = page_url || '/api/products/find'
            axios.post(page_url,
                {
                    name: this.product_name_find,
                    product_category: this.product_category
                },
                {
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                    .then((res) => {
                        this.products = []
                        this.products = res.data.data
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
            this.product_category = 'tatcatheloai'
            this.product_name_find = null
            this.findProduct()
        },
        addProductToCart(product){
            if(product.price != null){
                    let data = {
                    id: product.id,
                    discount: 0,
                    name: product.name,
                    code: product.code,
                    unit: product.unit,
                    image: product.image,
                    quantity: 1,
                    oldquantity: product.quantity,
                    price: product.price,
                    totalprice: product.price,
                }
                if(this.carts.length === 0){
                    this.carts.push(data)
                }else if(this.carts.length > 0){
                    const cartItem = this.carts.find(cart => cart.id === product.id)
                    if(cartItem){
                        alert('Mặt hàng đã tồn tại trong hóa đơn, hãy điều chỉnh số lượng bên dưới!!!')
                    }else{
                        this.carts.push(data)
                    }
                }
            }else {
                alert('Bạn cần chọn giá trước khi thêm vào hóa đơn')
            }
        },
        removeProductfromCart(index){
            this.carts.splice(index,1)
        },
        removeAllProductfromCart(){
            if(confirm("Bạn có muốn xóa hết các mặt hàng trong hóa đơn?")){
                this.carts = []
            }
        },
        changeQuantity(product){
            if(this.kind === 'banhang'){
                if(product.quantity > product.oldquantity){
                    product.quantity = product.oldquantity
                }
            }
            if(product.discount > 100){
                product.discount = 100
            }
            if(product.discount < 0){
                product.discount = 0
            }
            product.totalprice = (product.price * product.quantity) - (product.price * product.quantity)*(product.discount / 100)
        },
        changePrice(product,price_type_cost){
            product.price = price_type_cost
        },
        createOrder(){
            this.order_success = null
            this.success = false
            if(!this.carts.length){
                alert('Cần ít nhất một mặt hàng để lập hóa đơn')
            }
            else if(this.searchQuery === ''){
                alert('Hãy nhập tên khách hàng')
            }
            else if(this.customer.id === '' && this.searchQuery !== '' && this.addCustomer === 'themmoi'){
                if(confirm('Bạn có muốn lập hóa đơn và thêm khách hàng mới?')){
                    let items = JSON.stringify(this.carts)
                    let data = {
                        kind: this.kind,
                        carts: items,
                        customer_name: this.searchQuery,
                        tax: this.tax,
                        tax_code: this.tax_code,
                        discount: this.discount,
                        totalprice: this.totalPrice,
                        totalPriceWithoutTaxAndDiscount: this.totalpriceAfterTaxAndDiscount,
                    }
                    axios.post('/api/orders/add2',data, {
                        headers: {
                            "Authorization": 'Bearer '+this.currentUser.token
                        }
                    })
                    .then((response) => {
                        this.order_success = response.data.order
                        this.totalPriceOrderSuccess = response.data.totalPrice
                        this.totalPriceAfterTaxOrderSuccess = response.data.totalPriceAfterTax
                        this.taxOrderSuccess = response.data.tax
                        this.success = true
                        this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
                        this.findProduct()
                        this.fetchCustomers()
                        this.try()
                    })
                    .catch((error) => {
                        this.alert ='Lưu thất bại <i class="fas fa-times"></i>'
                        if(error.response.status == 422){
                            this.errors = error.response.data.errors
                        }
                        this.success = false
                    })
                }
            }else if(this.customer.id === '' && this.searchQuery !== '' && this.addCustomer === 'khongthem'){
                    let items = JSON.stringify(this.carts)
                    let data = {
                        kind: this.kind,
                        carts: items,
                        customer_name: this.searchQuery,
                        tax: this.tax,
                        tax_code: this.tax_code,
                        discount: this.discount,
                        totalprice: this.totalPrice,
                        totalPriceWithoutTaxAndDiscount: this.totalpriceAfterTaxAndDiscount,
                    }
                    axios.post('/api/orders/add3',data, {
                        headers: {
                            "Authorization": 'Bearer '+this.currentUser.token
                        }
                    })
                    .then((response) => {
                        this.order_success = response.data.order
                        this.totalPriceOrderSuccess = response.data.totalPrice
                        this.totalPriceAfterTaxOrderSuccess = response.data.totalPriceAfterTax
                        this.taxOrderSuccess = response.data.tax
                        this.success = true
                        this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
                        this.findProduct()
                        this.fetchCustomers()
                        this.try()
                    })
                    .catch((error) => {
                        this.alert ='Lưu thất bại <i class="fas fa-times"></i>'
                        if(error.response.status == 422){
                            this.errors = error.response.data.errors
                        }
                        this.success = false
                    })
            }
            else if(this.customer.id !== ''){
                if(confirm('Bạn có muốn lập hóa đơn với khách hàng: '+this.customer.name+'?')){
                    let items = JSON.stringify(this.carts)
                    let data = {
                        kind: this.kind,
                        carts: items,
                        customer_id: this.customer.id,
                        tax: this.tax,
                        tax_code: this.tax_code,
                        discount: this.discount,
                        totalprice: this.totalPrice,
                        totalPriceWithoutTaxAndDiscount: this.totalpriceAfterTaxAndDiscount
                    }
                    axios.post('/api/orders/add1',data, {
                        headers: {
                            "Authorization": 'Bearer '+this.currentUser.token
                        }
                    })
                    .then((response) => {
                        this.order_success = response.data.order
                        this.totalPriceOrderSuccess = response.data.totalPrice
                        this.totalPriceAfterTaxOrderSuccess = response.data.totalPriceAfterTax
                        this.taxOrderSuccess = response.data.tax
                        this.success = true
                        this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
                        this.findProduct()
                        this.fetchCustomers()
                        this.try()
                    })
                    .catch((error) => {
                        this.alert ='Lưu thất bại <i class="fas fa-times"></i>'
                        if(error.response.status == 422){
                            this.errors = error.response.data.errors
                        }
                        this.success = false
                    })
                }
            }
        },
        setActiveIndexPrice(index , product){
            this.activeIndexPrice = index
            this.tempPrice = null
        },
        unsetActiveIndexPrice(){
            this.activeIndexPrice = null
            this.tempPrice = null
        },
        changeCartItemPrice(item){
            item.price = this.tempPrice
            this.changeQuantity(item)
            this.tempPrice = 0
            this.activeIndexPrice = null
        },
        closeModal(){
            this.modal = false
            this.customer = {
                id: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                tax_code: '',
                sorting: '',
            }
        },
        setCustomer(result){
            this.tax_code = result.tax_code
            this.searchQuery = result.name
            this.customer = result
            this.modal = false
        },
        removeSelectedCustomer(){
            this.customer = {
                id: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                tax_code: '',
                sorting: '',
            }
        },
        printOrder(){
            this.$htmlToPaper('hoadon');
        },
        try(){
            let vm = this
            var checkExist = setInterval(() => {
                if(vm.order_success !== null && vm.order_success.kind !== 'nhaphang') {
                    console.log("Exists!");
                    clearInterval(checkExist);
                    vm.printOrder()
                }
            }, 100);
        },
        DocSo3ChuSo(baso){
            var ChuSo=new Array(" không "," một "," hai "," ba "," bốn "," năm "," sáu "," bảy "," tám "," chín ");

            var tram;
            var chuc;
            var donvi;
            var KetQua="";
            tram=parseInt(baso/100);
            chuc=parseInt((baso%100)/10);
            donvi=baso%10;
            if(tram==0 && chuc==0 && donvi==0) return "";
            if(tram!=0)
            {
                KetQua += ChuSo[tram] + " trăm ";
                if ((chuc == 0) && (donvi != 0)) KetQua += " lẻ ";
            }
            if ((chuc != 0) && (chuc != 1))
            {
                    KetQua += ChuSo[chuc] + " mươi";
                    if ((chuc == 0) && (donvi != 0)) KetQua = KetQua + " lẻ ";
            }
            if (chuc == 1) KetQua += " mười ";
            switch (donvi)
            {
                case 1:
                    if ((chuc != 0) && (chuc != 1))
                    {
                        KetQua += " mốt ";
                    }
                    else
                    {
                        KetQua += ChuSo[donvi];
                    }
                    break;
                case 5:
                    if (chuc == 0)
                    {
                        KetQua += ChuSo[donvi];
                    }
                    else
                    {
                        KetQua += " lăm ";
                    }
                    break;
                default:
                    if (donvi != 0)
                    {
                        KetQua += ChuSo[donvi];
                    }
                    break;
                }
            return KetQua;
        },
        DocTienBangChu(SoTien){
            var Tien=new Array(" ", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ");

            var lan=0;
            var i=0;
            var so=0;
            var KetQua="";
            var tmp="";
            var ViTri = new Array();
            if(SoTien<0) return "Số tiền âm !";
            if(SoTien==0) return "Không đồng !";
            if(SoTien>0)
            {
                so=SoTien;
            }
            else
            {
                so = -SoTien;
            }
            if (SoTien > 8999999999999999)
            {
                //SoTien = 0;
                return "Số quá lớn!";
            }
            ViTri[5] = Math.floor(so / 1000000000000000);
            if(isNaN(ViTri[5]))
                ViTri[5] = "0";
            so = so - parseFloat(ViTri[5].toString()) * 1000000000000000;
            ViTri[4] = Math.floor(so / 1000000000000);
            if(isNaN(ViTri[4]))
                ViTri[4] = "0";
            so = so - parseFloat(ViTri[4].toString()) * 1000000000000;
            ViTri[3] = Math.floor(so / 1000000000);
            if(isNaN(ViTri[3]))
                ViTri[3] = "0";
            so = so - parseFloat(ViTri[3].toString()) * 1000000000;
            ViTri[2] = parseInt(so / 1000000);
            if(isNaN(ViTri[2]))
                ViTri[2] = "0";
            ViTri[1] = parseInt((so % 1000000) / 1000);
            if(isNaN(ViTri[1]))
                ViTri[1] = "0";
            ViTri[0] = parseInt(so % 1000);
            if(isNaN(ViTri[0]))
                    ViTri[0] = "0";
                if (ViTri[5] > 0)
                {
                    lan = 5;
                }
                else if (ViTri[4] > 0)
                {
                    lan = 4;
                }
                else if (ViTri[3] > 0)
                {
                    lan = 3;
                }
                else if (ViTri[2] > 0)
                {
                    lan = 2;
                }
                else if (ViTri[1] > 0)
                {
                    lan = 1;
                }
                else
                {
                    lan = 0;
                }
                for (i = lan; i >= 0; i--)
                {
                tmp = this.DocSo3ChuSo(ViTri[i]);
                KetQua += tmp;
                if (ViTri[i] > 0) KetQua += Tien[i];
                if ((i > 0) && (tmp.length > 0)) KetQua += ',';//&& (!string.IsNullOrEmpty(tmp))
            }
            if (KetQua.substring(KetQua.length - 1) == ',')
            {
                    KetQua = KetQua.substring(0, KetQua.length - 1);
            }
            KetQua = KetQua.substring(1,2).toUpperCase()+ KetQua.substring(2) + ' đồng';
            return KetQua;//.substring(0, 1);//.toUpperCase();// + KetQua.substring(1);
        }
    }
}
</script>

<style scoped>
.button-find{
    width: 150px;
}
.order {
  height: 500px;
  overflow-y: scroll;
}
.results{
    overflow-y: scroll;
    height: auto;
    max-height: 200px;
    list-style-type: none;
    margin: 0;
    padding: 0px;
    background: white;
    color: black;
}
.results-close{
    margin: 0;
    padding: 0px;
    color: red;
    font-weight: bold;
}

.results-item{
    cursor: pointer;
    padding-left: 5px;
}
.results-item:hover {
    border: 1px dashed black;
    background: lightcoral;
    color: black;
    font-weight: bold;
    font-size: 12pt;
}
.price-item{
    transition: 0.5 ease-in-out;
}
.price-item:hover {
    text-decoration: underline;
    cursor: pointer;
}
.price-item:active {
    color: red;
}
</style>
