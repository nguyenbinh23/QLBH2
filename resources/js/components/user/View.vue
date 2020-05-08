<template>
    <div>
       <div class="text-center">
           <h4><i class="fas fa-cog"></i> Sửa tài khoản</h4>
       </div>
        <!-- <router-link  to="/admin/users" class="btn btn-outline-success btn-sm mb-2"><i class="fa fa-balance-scale" aria-hidden="true"></i> Danh sách tài khoản</router-link> -->
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-8" v-if="user">
                <div class="form-group">
                    <label for="UserEmail">Email </label>
                    <input type="text" disabled id="UserEmail" class="form-control" v-model="user.email">
                </div>
                <div class="form-group">
                    <label for="UserName">Tên tài khoản: </label>
                    <input type="email" id="UserName" class="form-control" v-model="user.name">
                </div>
                <p><span v-if="errors.name" class="badge badge-danger">{{ errors.name[0] }}</span></p>
                <form class="form-inline mb-3" @click.stop.prevent>
                    <div class="form-group">
                        <button :class="{'btn-success':picked == false}" class="btn btn-secondary form-control" @click="handlePicked()">Mật khẩu cũ</button>
                        <button :class="{'btn-success':picked == true}" class="btn btn-secondary form-control ml-0 ml-sm-2 ml-md-2" @click="handlePicked()">Mật khẩu mới</button>
                    </div>
                </form>
                <template v-if="picked">
                    <transition name="slide-fade">
                    <div class="form-group">
                        <label for="UserNewPass">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="UserNewPass" v-model="user.new_password">
                         <p><span v-if="errors.new_password" class="badge badge-danger">{{ errors.new_password[0] }}</span></p>
                        <label for="UserConfirmPass">Nhập lại khẩu mới</label>
                        <input type="password" class="form-control" id="UserConfirmPass" v-model="user.password_confirm">
                         <p><span v-if="errors.new_password" class="badge badge-danger">{{ errors.new_password[0] }}</span></p>
                    </div>
                    </transition>
                </template>
                <div class="form-group" v-if="alert">
                    <p class="text-center" v-html="alert">{{alert}}</p>
                </div>
                <div class="form-group mt-5">
                    <button class="btn btn-success form-control" @click="editUser">Lưu tài khoản</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            errors: [],
            alert: null,
            picked: false,
            user: null,
            alert: null,
        }
    },
    created(){
        if(this.currentUser !== null){
            let User = this.currentUser
            this.user = {
                id: User.id,
                name: User.name,
                email: User.email,
                new_password: '',
                password_confirm: ''
            }
        }
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
    },
    methods: {
        editUser(){
            this.errors = []
            this.alert = null
            let formData = new FormData()
            formData.append('id',this.currentUser.id)
            formData.append('name',this.user.name)
            formData.append('picked',this.picked)
            if(this.picked == true){
                formData.append('new_password',this.user.new_password)
                formData.append('password_confirm',this.user.password_confirm)
            }

            axios.post('/api/users',formData,{
                headers: {
                    "Authorization": 'Bearer '+this.currentUser.token
                }
            })
            .then((response) => {
                this.alert = 'Đã Lưu <i class="fas fa-check"></i>'
                this.currentUser.name = response.data.name
                localStorage.setItem("user", JSON.stringify( this.currentUser));
            })
            .catch((error) => {
                this.alert ='Lưu thất bại <i class="fas fa-times"></i>'
                if(error.response.status == 422){
                    this.errors = error.response.data.errors
                }
            })
        },
        handlePicked(){
            this.picked = !this.picked
        }
    }
}
</script>
