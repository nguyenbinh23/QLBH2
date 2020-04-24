<template>
    <div class="row justify-content-center">
        <div class="col-md-4 mt-5">
            <div class="card">
                <div class="card-header bg-success text-white">Đăng nhập</div>
                <div class="card-body bg-dark text-white">
                    <form @submit.prevent>
                        <div class="form-group row">
                            <label for="Email">Email</label>
                            <input type="email" v-model="form.email" autocomplete  class="form-control" placeholder="Nhập email">
                        </div>
                        <div class="form-group row">
                            <label for="Password">Mật khẩu</label>
                            <input type="password" v-model="form.password" class="form-control" placeholder="Nhập mật khẩu">
                        </div>
                        <div class="form-group text-center" v-if="authError !== null">
                            <h5><span class="badge badge-danger mt-2"><i class="fas fa-times"></i> Đăng nhập thất bại, hãy kiểm tra lại email và mật khẩu</span></h5>
                        </div>
                        <div class="form-group text-center" v-if="success">
                            <h5><span class="badge badge-info mt-2"><i class="fas fa-check"></i> {{ success }}, Hãy đăng nhập để tiếp tục</span></h5>
                        </div>
                         <div class="form-group row">
                            <button @keyup.enter="authenticate" @click="authenticate" class="btn btn-success form-control"><i class="fas fa-sign-in-alt"></i> Đăng nhập</button>
                        </div>
                        <div class="form-group row">
                            <router-link to="/register" class="btn btn-warning form-control"><i class="fas fa-pen-square"></i> Đăng ký</router-link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { login } from '../../helpers/auth'
export default {
    name: 'login',
    data(){
        return {
            form: {
                email: this.$route.query.email || null,
                password: '',
            },
            error: null,
            success: this.$route.query.success || null
        }
    },
    computed: {
        currentUser(){
              return this.$store.getters.currentUser
        },
        authError(){
            return this.$store.getters.authError
        },
    },
    methods: {
        authenticate(){
            this.success = null
            this.$store.state.auth_error = null
            this.$store.dispatch('login')
            login(this.form)
                .then((res) => {
                    this.$store.commit("loginSuccess", res)
                    if(this.currentUser.quyenhan === 'admin'){
                        this.$router.push({ path: '/admin'})
                    }else{
                        this.$router.push({ path: '/'})
                    }
                })
                .catch((error) => {
                    this.$store.commit("loginFailed", { error })
                })
        }
    }
}
</script>
<style scoped>
.error {
    text-align: center;
    color: red
}
</style>
