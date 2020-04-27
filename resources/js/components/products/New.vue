<template>
    <div>
       <div class="text-center">
           <h4><i class="fas fa-user-plus"></i> Thêm hàng hóa</h4>
       </div>
        <router-link  to="/admin/products" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-clipboard-list"></i> Danh sách hàng hóa</router-link>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <label for="ProductName">Tên hàng hóa:</label>
                    <input type="text" id="ProductName" class="form-control" v-model="product.name">
                </div>
                <p><span v-if="errors.name" class="badge badge-danger">{{ errors.name[0] }}</span></p>
                <div class="form-group">
                    <label for="ProductCode">Mã hàng hóa: </label>
                    <input type="email" id="ProductCode" class="form-control" v-model="product.code">
                </div>
                <p><span v-if="errors.code" class="badge badge-danger">{{ errors.code[0] }}</span></p>
                <div class="form-group">
                    <label for="ProductDescription">Mô tả hàng hóa: </label>
                    <textarea type="text" id="ProductDescription" class="form-control" v-model="product.description"></textarea>
                </div>
                <p><span v-if="errors.description" class="badge badge-danger">{{ errors.description[0] }}</span></p>
                <div class="form-group">
                    <label for="ProductQuantity">Số lượng: </label>
                    <input type="text" id="ProductQuantity" class="form-control" v-model="product.quantity">
                </div>
                <p><span v-if="errors.quantity" class="badge badge-danger">{{ errors.quantity[0]}}</span></p>

                <div class="form-group">
                      <label for="Category">Chọn loại hàng hóa</label>
                      <select class="form-control" id="Category" v-model="product.category_id">
                        <option v-for="category in categories" :key="category.id" :value="category.id">- {{category.category_name}} -</option>
                      </select>
                </div>
                <p><span v-if="errors.category_id" class="badge badge-danger">{{ errors.category_id[0]}}</span></p>
                <div class="form-group">
                      <label for="Unit">Chọn đơn vị</label>
                      <select class="form-control" id="Unit" v-model="product.unit_id">
                        <option v-for="unit in units" :key="unit.id" :value="unit.id">- {{unit.name}} -</option>
                      </select>
                </div>
                <p><span v-if="errors.unit_id" class="badge badge-danger">{{ errors.unit_id[0]}}</span></p>
                <div v-for="(price_type, index) in price_types" :key="price_type.index" class="form-group">
                    <span class="float-right" style="cursor:pointer" @click="deletePriceType(index)">X</span>
                    <label>Loại giá:</label>
                    <input type="text" class="form-control mb-3" v-model="price_type.name">
                    <label>Nhập Giá:</label>
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" v-model="price_type.cost">
                        </div>
                        <div class="col">
                            <h3 class="form-inline"><span class="badge badge-danger">{{price_type.cost | currency('VND', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-success" @click="addMorePriceType()">Thêm loại giá</button>
                </div>

                <div class="form-group mt-3">
                    <label for="Image" >Hình đại điện cho hàng hóa</label>
                    <input type="file" class="form-inline" ref="files2" />
                    <br>
                </div>

                <div class="form-group mt-3">
                    <label for="ProductUnit">Các hình ảnh mô tả</label>
                    <input type="file" class="form-inline" id="files" ref="files" multiple />
                    <br>
                </div>

                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html="alert">{{alert}}</p>
                </div>
                <div class="form-group mt-5">
                    <button class="btn btn-success form-control" @click="addProduct">Lưu hàng hóa</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            product: {
                name: '',
                code: '',
                description: '',
                quantity: '',
                category_id: '',
                unit_id: '',
            },
            price_types : [
                {
                    name: 'Giá nhập',
                    cost: '0'
                },
                {
                    name: 'Giá bán sỉ',
                    cost: '0'
                },
                {
                    name: 'Giá bán lẻ',
                    cost: '0'
                },
            ],
            product_image: {
                name: ''
            },
            categories: [],
            units: [],
            selected_category: null,
            files: [],
            errors: [],
            alert: null
        }
    },
    created(){
        this.fetchCategories()
        this.fetchUnits()
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
    },
    methods: {
        fetchCategories(){
            axios.get('http://localhost:8000/api/categories/all',{
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
        fetchUnits(){
            axios.get('http://localhost:8000/api/units/all',{
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                .then((res) => {
                    this.units = res.data
                })
                .catch(() => {
                    console.log('Lấy đơn vị thất bại!!!')
                })
        },
        addMorePriceType(){
            this.price_types.push({
                name: 'Giá ...',
                cost: '0'
            })
        },
        deletePriceType(index){
            this.price_types.splice(index ,1)
        },
        addProduct(){
            this.alert = null
            this.errors = []
            let formData = new FormData()

            this.files = this.$refs.files.files
            for( var i = 0; i < this.files.length; i++ ){
                let file = this.files[i];
                formData.append('files['+i+']',file);
            }
            if(typeof this.$refs.files2.files[0] !== 'undefined'){
                formData.append('product_image',this.$refs.files2.files[0])
            }
            formData.append('name',this.product.name)
            formData.append('code',this.product.code)
            formData.append('description',this.product.description)
            formData.append('unit_id',this.product.unit_id)
            formData.append('quantity',this.product.quantity)
            formData.append('category_id',this.product.category_id)
            formData.append('price_types',JSON.stringify(this.price_types))

            axios.post('/api/products/add',formData, {
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((response) => {
                this.success = true
                this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
            })
            .catch((error) => {
                this.alert ='Lưu thất bại <i class="fas fa-times"></i>'
                if(error.response.status == 422){
                    this.errors = error.response.data.errors
                }
            })
        }
    }
}
</script>
<style scoped>
</style>
