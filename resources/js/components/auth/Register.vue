<template>
    <div class="row justify-content-center">
        <div class="col-md-4 mt-5">
            <div class="card">
                <div class="card-header bg-success text-white">Đăng ký</div>
                <div class="card-body bg-dark text-white">
                    <form @submit.prevent>
                        <div class="form-group row">
                            <label for="UserName">Họ và tên</label>
                            <input type="text" v-model="form.name" class="form-control"  placeholder="Nhập họ và tên">
                            <span v-if="errors.name" class="badge badge-danger mt-2">{{ errors.name[0] }}</span>
                        </div>

                        <div class="form-group row">
                            <label for="Email">Email</label>
                            <input type="email" v-model="form.email" class="form-control" placeholder="Nhập email">
                            <span v-if="errors.email" class="badge badge-danger mt-2">{{ errors.email[0] }}</span>
                        </div>

                        <div class="form-group row">
                            <label for="Password">Mật khẩu</label>
                            <input type="password" v-model="form.password" class="form-control"  autocomplete  placeholder="Nhập mật khẩu">
                            <span v-if="errors.password" class="badge badge-danger mt-2">{{ errors.password[0] }}</span>
                        </div>

                        <div class="form-group row">
                            <label for="Password">Nhập lại mật khẩu</label>
                            <input type="password" v-model="form.password_confirm" class="form-control" autocomplete  placeholder="Nhập lại mật khẩu">
                            <span v-if="errors.password_confirm" class="badge badge-danger mt-2">{{ errors.password_confirm[0] }}</span>
                        </div>

                        <div class="form-group row">
                            <span v-if="success" class="badge badge-success">{{ success }}</span>
                            <button  @keydown.enter="registerUser"  @click="registerUser" class="btn btn-success form-control"><i class="fas fa-pen-square"></i> Đăng ký</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'register',
    data(){
        return {
            form: {
                email: '',
                name: '',
                password: '',
                password_confirm: '',
            },
            errors: {},
            success: null

        }
    },
    methods: {
        registerUser(){
            this.success = null,
            this.errors = {},
            axios.post('http://localhost:8000/api/auth/register',this.form)
                .then((res) => {
                    this.success = res.data.success
                    this.$router.push({ path: '/login', name: 'login',  query: { success: this.success , email: this.form.email }})
                })
                .catch((error) => {
                    if(error.response){
                        if(error.response.status == 422){
                            this.errors = error.response.data.errors
                        }
                    }
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
