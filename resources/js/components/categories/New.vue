<template>
    <div>
       <div class="text-center">
           <h4><i class="fas fa-plus"></i> Thêm thể loại</h4>
       </div>
        <router-link  to="/admin/categories" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-clipboard-list"></i> Danh sách thể loại</router-link>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <label for="CustomerName">Tên thể loại: </label>
                    <input autofocus type="text" id="CustomerName" class="form-control" v-model="category.category_name">
                </div>
                <p><span v-if="errors.category_name" class="badge badge-danger">{{ errors.category_name[0] }}</span></p>
                <div class="form-group">
                    <label for="CustomerEmail">Mã thể loại: </label>
                    <input type="email" id="CustomerEmail" class="form-control" v-model="category.category_code">
                </div>
                <p><span v-if="errors.category_code" class="badge badge-danger">{{ errors.category_code[0] }}</span></p>
                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html="alert"></p>
                </div>
                <div class="form-group mt-5">
                    <button class="btn btn-success form-control" @click="addCategory">Lưu thể loại</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            category: {
                category_name: '',
                category_code: '',
            },
            errors: [],
            alert: null
        }
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
    },
    methods: {
        addCategory(){
            this.alert = null
            this.errors = []
            axios.post('/api/categories/add',this.category, {
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
