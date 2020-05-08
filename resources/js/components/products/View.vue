<template>
    <div>
       <div class="text-center">
           <h4><i class="fas fa-user-plus"></i> Sửa hàng hóa</h4>
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
                <div v-for="(price_type, index) in product.pricelist" :key="price_type.index" class="form-group">
                    <span class="float-right" style="cursor:pointer" @click="deletePriceType(index)">X</span>
                    <label>Loại giá:</label>
                    <input type="text" class="form-control mb-3" v-model="price_type.name">
                    <label>Nhập Giá:</label>
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" v-model="price_type.cost">
                        </div>
                        <div class="col">
                            <h3 class="form-inline"><span class="badge badge-danger">{{price_type.cost | currency('đ', 0 , { thousandsSeparator: ',' , spaceBetweenAmountAndSymbol:true   ,symbolOnLeft: false}) }}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-success" @click="addMorePriceType()">Thêm loại giá</button>
                </div>
                <form class="form-inline mb-3" @click.stop.prevent>
                    <div class="form-group">
                        <button :class="{'btn-success':picked === 'hinhanhcu'}" class="btn btn-secondary form-control" @click="oldImage()">Dùng ảnh cũ</button>
                        <button :class="{'btn-success':picked === 'hinhanhmoi'}" class="btn btn-secondary form-control ml-0 ml-sm-2 ml-md-2" @click="newImage()">Thay đổi ảnh mới</button>
                    </div>
                </form>
                <template v-if="picked === 'hinhanhcu'">
                    <h4>Hình ảnh đại diện:</h4>
                    <div class="row">
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <div class="card text-left">
                                <img class="card-img-top" :src="`/storage/cover_images/${product.image}`" style="height: 150px;">
                            </div>
                        </div>
                    </div>
                    <h4>Các hình ảnh mô tả: </h4>
                    <div class="row">
                        <div class="col-6 col-md-4 col-lg-3 mb-3" v-for="image in product.image_list" :key="Math.random()+image" v-if="image !== 'noimage.jpg'">
                            <div class="card text-left">
                              <img class="card-img-top" :src="`/storage/cover_images/${image}`" style="height: 150px;" alt="">
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 mb-3" v-if="product.image_list && product.image_list.length === 1 && product.image_list[0] === 'noimage.jpg'">
                             <div class="card text-left">
                                <img class="card-img-top" :src="`/storage/cover_images/noimage.jpg`" style="height: 150px;" alt="">
                             </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="form-group">
                        <h4>Hình ảnh đại diện:</h4>
                        <div class="row">
                            <div class="col-6 col-md-4 col-lg-3 mb-2">
                               <div class="card text-left">
                                  <img class="card-img-top" :src="`/storage/cover_images/${product.image}`" style="height: 150px;">
                               </div>
                            </div>
                        </div>
                        <h4>Các hình ảnh mô tả:</h4>
                        <div class="row">
                            <div class="col-6 col-md-4 col-lg-3 mb-2" v-for="(image,index) in product.image_list" :key="Math.random()+image" v-if="image !== 'noimage.jpg'">
                                <div class="card text-left">
                                    <img  class="card-img-top" :src="`/storage/cover_images/${image}`" style="height: 150px;" alt="">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-danger form-control" @click="deleteImage(image,index)">X</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="Image" >Đổi hình ảnh đại diện: </label>
                        <input type="file" class="form-inline" ref="files2" />
                        <br>
                    </div>
                    <div class="form-group mt-3">
                        <label for="ProductUnit">Thêm hình ảnh mô tả: </label>
                        <input type="file" class="form-inline" id="files" ref="files" multiple />
                        <br>
                    </div>
                        <template v-if="errors">
                            <h5 v-if="k.indexOf('files.') !== -1"  v-for="(error,k,index) in errors" :key="error+Math.random()">
                                <template v-if="error[0] === 'max'">
                                    <span class="badge badge-danger">
                                            File {{String(k).slice(6,9)}} phải nhỏ hơn 5MB
                                    </span>
                                </template>
                                <template v-else>
                                    <span class="badge badge-danger">
                                            File {{String(k).slice(6,7)}} phải có đuôi là jpg,jpeg,png,bmp,tiff
                                    </span>
                                </template>
                            </h5>
                        </template>
                </template>
                <h5><span class="badge badge-danger" v-if="payloadError">{{payloadError}}</span></h5>
                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html="alert">{{alert}}</p>
                </div>
                <div class="form-group mt-5">
                    <button v-if="isDisabled" class="btn btn-success form-control" @click="editProduct">Lưu hàng hóa</button>
                    <button disabled v-else  class="btn btn-success form-control"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i> Đang lưu {{percent}} %</button>
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
            percent: 0,
            deleteImages: [],
            categories: [],
            units: [],
            selected_category: null,
            files: [],
            errors: [],
            payloadError: null,
            alert: null,
            picked: 'hinhanhcu',
            isDisabled: true,
        }
    },
    created(){
        this.fetchProduct(this.$route.params.id)
        this.fetchCategories()
        this.fetchUnits()
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
    },
    methods: {
        fetchProduct(id){
              axios.get('/api/products/'+id,{
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                .then((res) => {
                    this.product = res.data
                    this.product.image_list = JSON.parse(res.data.image_list)
                })
                .catch(() => {
                    console.log('Lấy thể loại thất bại!!!')
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
        fetchUnits(){
            axios.get('/api/units/all',{
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
            this.product.pricelist.push({
                name: 'Giá ...',
                cost: '0'
            })
        },
        deletePriceType(index){
            this.product.pricelist.splice(index ,1)
        },
        editProduct(){
            this.payloadError = null
            this.alert = null
            this.errors = []
            let formData = new FormData()
            this.isDisabled = false


            if(this.picked === "hinhanhmoi"){
                formData.append('old_image_list',JSON.stringify(this.product.image_list))
                formData.append('delete_images',JSON.stringify(this.deleteImages))
                formData.append('old_image',this.product.image)

                if(typeof this.$refs.files2.files[0] !== 'undefined'){
                    formData.append('product_image',this.$refs.files2.files[0])
                }

                this.files = this.$refs.files.files
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('files['+i+']',file);
                }
            }

            formData.append('id',this.product.id)
            formData.append('name',this.product.name)
            formData.append('picked',this.picked)
            formData.append('code',this.product.code)
            formData.append('description',this.product.description)
            formData.append('unit_id',this.product.unit_id)
            formData.append('quantity',this.product.quantity)
            formData.append('category_id',this.product.category_id)
            formData.append('price_types',JSON.stringify(this.product.pricelist))

            axios.post('/api/products',formData, {
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                },
                onUploadProgress: progressEvent => {
                    this.percent = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
                }
            })
            .then((response) => {
                this.percent = 0
                this.success = true
                this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
                this.product = response.data
                this.product.image_list = JSON.parse(response.data.image_list)
                this.isDisabled = true
                if(this.picked === 'hinhanhmoi'){
                    this.$refs.files2.value = null
                    this.$refs.files.value = null
                }
            })
            .catch((error) => {
                this.isDisabled = true
                this.alert ='Lưu thất bại <i class="fas fa-times"></i>'
                if(this.picked === 'hinhanhmoi'){
                    this.$refs.files2.value = null
                    this.$refs.files.value = null
                }
                if(error.response.status == 422){
                    this.errors = error.response.data.errors
                }
                if(error.response.status == 413){
                    this.payloadError = 'Bạn chỉ có thể gửi tối đa 50MB trong 1 lần'
                }
                this.percent = 0
            })
        },
        oldImage(){
            this.picked = 'hinhanhcu'
            this.deleteImages = []
            axios.get('/api/products/'+this.$route.params.id,{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((res) => {
                this.product.image_list = JSON.parse(res.data.image_list)
            })
            .catch(() => {
                console.log('Lấy thể loại thất bại!!!')
            })
        },
        newImage(){
            this.picked = 'hinhanhmoi'
            this.deleteImages = []
            axios.get('/api/products/'+this.$route.params.id,{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((res) => {
                this.product.image_list = JSON.parse(res.data.image_list)
            })
            .catch(() => {
                console.log('Lấy thể loại thất bại!!!')
            })
        },
        deleteImage(image,index){
            this.product.image_list.splice(index,1)
            this.deleteImages.push(image)
        }
    }
}
</script>
<style scoped>
</style>
