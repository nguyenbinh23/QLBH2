<template>
    <div>
        <div class="text-center">
           <h4><i class="fas fa-clipboard-list"></i> Danh sách thể loại</h4>
       </div>
        <router-link  to="/admin/categories/new" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-user-plus"></i> Thêm mới thể loại</router-link>
        <button class="btn btn-outline-warning btn-sm mb-2" @click="fetchCategories()"><i class="fa fa-sync-alt"></i> Làm mới danh sách</button>
       <div class="input-group mb-3">
            <input @keydown.enter="findCategory()" type="text" class="form-control" placeholder="Nhập tên thể loại cần tìm..." v-model="category_name_find">
            <div class="input-group-append">
                <button class="btn btn-success button-find" title="Tìm kiếm" @click="findCategory()"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <template v-if="!categories.length">
            <div class="row justify-content-center">
                <h3>Không có thể loại</h3>
            </div>
        </template>

        <template v-else>
            <div class="row justify-content-center">
                <div class="col-6 col-md-4">
                    <table class="table table-bordered table-responsive text-white text-center">
                        <thead>
                            <th scope="col" width="10%">#</th>
                            <th scope="col" width="30%">Tên thể loại</th>
                            <th scope="col" width="30%">Mã thể loại</th>
                            <th scope="col" width="15%">Sửa</th>
                            <th scope="col" width="15%">Xóa</th>
                        </thead>
                         <transition-group tag="tbody" name="view">
                            <tr v-for="(category,index) in categories" :key="category.id">
                                <th>{{parseInt(index + 1)}}</th>
                                <td>{{category.category_name}}</td>
                                <td>{{category.category_code}}</td>
                                <td><router-link :to="{name: 'category-edit' , params: {id: category.id}}" class="btn btn-warning">Sửa</router-link></td>
                                <td><button class="btn btn-danger" @click="removeCategory(index,category.id)">Xóa</button></td>
                            </tr>
                         </transition-group>
                    </table>
                </div>
            </div>
        </template>
        <nav aria-label="...">
            <ul class="pagination mt-2 justify-content-center">
                <li v-show="firstpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2"  @click="fetchCategories(firstpageURL)"> First </a>
                </li>
                <li v-show="current_page" class="page-item">
                    <a  class="btn btn-outline-primary ml-2">{{current_page}}</a>
                </li>
                <li v-show="nextpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2" @click="fetchCategories(nextpageURL)"> Next </a>
                </li>
                <li v-show="prevpageURL" class="page-item">
                    <a  class="btn btn-outline-warning ml-2" @click="fetchCategories(prevpageURL)"> Back </a>
                </li>
                <li v-show="lastURL" class="page-item">
                    <a  class="btn btn-outline-danger ml-2" @click="fetchCategories(lastURL)"> Last </a>
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
            categories: [],
            next_page_url: null,
            prev_page_url: null,
            last_page_url: null,
            first_page_url: null,
            current_page: 1,
            category_name_find: null,
            categoriesFind: []
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
    },
    methods: {
        fetchCategories(page_url){
            page_url = page_url || '/api/categories'
            axios.get(page_url,{
                headers: {
                    'Authorization': 'Bearer '+this.currentUser.token,
                }
            }).then((res) => {
                this.categories = res.data.data
                this.next_page_url = res.data.next_page_url
                this.prev_page_url = res.data.prev_page_url
                this.last_page_url = res.data.last_page_url
                this.first_page_url = res.data.first_page_url
                this.current_page = res.data.current_page
            }).catch((err) => {
                console.log(err)
            })
        },
        removeCategory(index,id){
            if(confirm('Bạn có muốn xóa thể loại này')){
                this.categories.splice(index,1)
                axios.post('/api/categories/remove',{
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

        findCategory(){
            axios.post('/api/categories/find',
                {
                    category_name: this.category_name_find,
                    category_code: this.category_name_find,
                },
                {
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                    .then((res) => {
                        this.categories = res.data.data
                        this.next_page_url = res.data.next_page_url
                        this.prev_page_url = res.data.prev_page_url
                        this.last_page_url = res.data.last_page_url
                        this.first_page_url = res.data.first_page_url
                        this.current_page = res.data.current_page
                    })
                    .catch((error) => {
                        console.log(error)
                    })
        }
    }
}
</script>

<style scoped>
.button-find{
    width: 150px;
}
</style>
