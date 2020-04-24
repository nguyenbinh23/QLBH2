<template>
    <div>
       <div class="text-center">
           <h4><i class="fas fa-cog"></i> Sửa thể loại</h4>
       </div>
        <router-link  to="/admin/categories" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-clipboard-list"></i> Danh sách thể loại</router-link>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <label for="CategoryName">Tên thể loại: </label>
                    <input type="text" id="CategoryName" class="form-control" v-model="category.category_name">
                </div>
                <p><span v-if="errors.category_name" class="badge badge-danger">{{ errors.category_name[0] }}</span></p>
                <div class="form-group">
                    <label for="CategoryCode">Mã thể loại: </label>
                    <input type="email" id="CategoryCode" class="form-control" v-model="category.category_code">
                </div>
                <p><span v-if="errors.category_code" class="badge badge-danger">{{ errors.category_code[0] }}</span></p>
                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html="alert">{{alert}}</p>
                </div>
                <div class="form-group mt-5">
                    <button class="btn btn-success form-control" @click="editCategory">Lưu thể loại</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            category: {},
            errors: [],
            alert: null
        }
    },
    created(){
        this.fetchCatebyId(this.$route.params.id)
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
    },
    methods: {
        fetchCatebyId(id){
            axios.get('/api/categories/'+id,{
                headers: {
                    'Authorization': 'Bearer '+this.currentUser.token,
                }
            })
            .then((response) => {
                this.category = response.data
            })
            .catch((error) => {
                console.log(error)
            })
        },
        editCategory(){
            this.errors = []
            axios.post('/api/categories',this.category, {
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((response) => {
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
