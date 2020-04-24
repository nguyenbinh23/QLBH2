<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                 <router-link  to="/admin/products" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-clipboard-list"></i> Danh sách sản phẩm</router-link>
                <div class="form-group">
                    <label for="File">Chọn file Excel:</label><br>
                    <input type="file" ref="file" id="File">
                    <div class="form-group mt-3" v-if="alert">
                        <p class="text-center" v-html="alert">{{alert}}</p>
                    </div>
                    <div class="form-group" v-for="error in errors" :key="error">
                        <p><span class="badge badge-danger">{{ error[0] }}</span></p>
                    </div>
                    <a @click="importProducts()" class="btn btn-success form-control mt-3">Nhập</a>
                </div>
                <template v-if="data_import.length">
                    <table class="table table-responsive table-light">
                        <thead>
                            <th>Tên Mặt Hàng</th>
                            <th>Mã hàng hóa</th>
                            <th>Mô tả</th>
                            <th>Đơn vị</th>
                            <th>Số lượng</th>
                            <th>Mã thể loại</th>
                        </thead>
                        <tbody>

                            <tr v-for="(item,index) in data_import" :key="Math.random()+item">
                                <template v-if="index > 0">
                                <th>{{item[0]}}</th>
                                <td>{{item[1]}}</td>
                                <td>{{item[2]}}</td>
                                <td>{{item[3]}}</td>
                                <td>{{item[4]}}</td>
                                <td>{{item[5]}}</td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </template>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            alert: null,
            errors: [],
            file: null,
            data_import: ''
        }
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
    },
    methods: {
        importProducts(){
            this.errors = null
            this.alert = null
            this.file = this.$refs.file.files[0]
            let formData = new FormData()
            formData.append('file',this.file)
            axios.post('/api/products/import',formData, {
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((response) => {
                this.data_import = response.data
                this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
            })
            .catch((error) => {
                 if(error.response.status == 422){
                    this.errors = error.response.data.errors
                }
            })
        }
    }
}
</script>
