<template>
    <div>
       <div class="text-center">
           <h4><i class="fas fa-plus"></i> Thêm đơn vị</h4>
       </div>
        <router-link  to="/admin/units" class="btn btn-outline-success btn-sm mb-2"><i class="fa fa-balance-scale" aria-hidden="true"></i> Danh sách đơn vị</router-link>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <label for="CustomerName">Tên đơn vị: </label>
                    <input autofocus type="text" id="CustomerName" class="form-control" v-model="unit.name">
                </div>
                <p><span v-if="errors.name" class="badge badge-danger">{{ errors.name[0] }}</span></p>
                <div class="form-group">
                    <label for="CustomerEmail">Mã đơn vị: </label>
                    <input type="email" id="CustomerEmail" class="form-control" v-model="unit.code">
                </div>
                <p><span v-if="errors.code" class="badge badge-danger">{{ errors.code[0] }}</span></p>
                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html="alert"></p>
                </div>
                <div class="form-group mt-5">
                    <button class="btn btn-success form-control" @click="addCategory">Lưu đơn vị</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            unit: {
                name: '',
                code: '',
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
            axios.post('/api/units/add',this.unit, {
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
