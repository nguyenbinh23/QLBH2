<template>
    <div>
       <div class="text-center">
           <h4><i class="fas fa-cog"></i> Sửa đơn vị</h4>
       </div>
        <router-link  to="/admin/units" class="btn btn-outline-success btn-sm mb-2"><i class="fa fa-balance-scale" aria-hidden="true"></i> Danh sách đơn vị</router-link>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-8">
                <div class="form-group">
                    <label for="UnitName">Tên đơn vị: </label>
                    <input type="text" id="UnitName" class="form-control" v-model="unit.name">
                </div>
                <p><span v-if="errors.name" class="badge badge-danger">{{ errors.name[0] }}</span></p>
                <div class="form-group">
                    <label for="UnitCode">Mã đơn vị: </label>
                    <input type="email" id="UnitCode" class="form-control" v-model="unit.code">
                </div>
                <p><span v-if="errors.code" class="badge badge-danger">{{ errors.code[0] }}</span></p>
                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html="alert">{{alert}}</p>
                </div>
                <div class="form-group mt-5">
                    <button class="btn btn-success form-control" @click="editCategory">Lưu đơn vị</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            unit: {},
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
            axios.get('/api/units/'+id,{
                headers: {
                    'Authorization': 'Bearer '+this.currentUser.token,
                }
            })
            .then((response) => {
                this.unit = response.data
            })
            .catch((error) => {
                console.log(error)
            })
        },
        editCategory(){
            this.errors = []
            axios.post('/api/units',this.unit, {
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
