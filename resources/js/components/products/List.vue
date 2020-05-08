<template>
    <div>
        <div class="text-center">
           <h4><i class="fas fa-clipboard-list"></i> Danh sách hàng hóa</h4>
       </div>
        <router-link  to="/admin/products/new" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-cart-plus"></i> Thêm Hàng Hóa</router-link>
        <button class="btn btn-outline-warning btn-sm mb-2" @click="newList()"><i class="fa fa-sync-alt"></i> Làm mới danh sách</button>
       <div class="input-group mb-3">
            <input @keyup.enter="findProduct()" type="text" class="form-control" placeholder="Nhập hàng hóa cần tìm..." v-model="product_name_find">
            <div class="input-group-append">
                <select class="btn btn-warning" v-model="product_category" @change="findProduct()">
                    <option value="tatcatheloai" selected>- Tất cả hàng hóa -</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">- {{category.category_name}} -</option>
                </select>
                <button class="btn btn-success button-find" title="Tìm kiếm" @click="findProduct()"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <template v-if="!products.length">
            <div class="row justify-content-center">
                <h3>Không có hàng hóa</h3>
            </div>
        </template>
        <template v-else>
        <table class="table table-bordered table-responsive text-white text-center">
            <thead>
                <!-- <th width="5%">#</th> -->
                <th width="5%">STT</th>
                <th width="10%">Hình ảnh</th>
                <th width="10%">Mã hàng hóa</th>
                <th width="20%">Tên hàng hóa</th>
                <th width="15%">Số lượng</th>
                <th width="10%">Đơn vị</th>
                <th width="15%">Bảng giá</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xóa</th>
            </thead>
             <transition-group tag="tbody" name="view"  mode="out-in">
                <tr v-for="(product,index) in products" :key="product.id">
                    <th>{{parseInt(index + 1)}}</th>
                    <td><img :src="'/storage/cover_images/'+product.image" style="width: 50px;height: 50px;"></td>
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
                            <select class="form-control">
                                <option v-for="price_type in product.pricelist" :key="price_type.id">{{price_type.name}}: {{price_type.cost | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</option>
                            </select>
                        </div>
                    </td>
                    <td><router-link :to="{name: 'product-edit' , params: {id: product.id}}" class="btn btn-warning">Sửa</router-link></td>
                    <td><button class="btn btn-danger" @click="removeProduct(index, product.id)">Xóa</button></td>
                </tr>
             </transition-group>
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
    </div>
</template>

<script>
export default {
    name: 'list',
    data(){
        return {
            products: [],
            categories: [],
            next_page_url: null,
            prev_page_url: null,
            last_page_url: null,
            first_page_url: null,
            current_page: 1,
            product_name_find: null,
            product_category: 'tatcatheloai',
            productsFind: []
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
        this.fetchCategories()
        this.findProduct()
    },
    methods: {
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
        removeProduct(index,id){
            if(confirm('Bạn có muốn xóa hàng hóa này')){
                this.products.splice(index,1)
                axios.post('/api/products/remove',{
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
                        console.log(error)
                        alert('Xóa thất bại')
                    })
            }
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
        }
    }
}
</script>

<style scoped>
.button-find{
    width: 150px;
}
</style>
