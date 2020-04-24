<template>
    <div>
         <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <router-link to="/"><a class="navbar-brand" href="#">MINHTUANTECH</a></router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active ml-2">
                        <router-link to="/"><a class="nav-link active btn btn-primary" href="#"> Đang phát triển</a></router-link>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 ">
                    <div v-if="!currentUser">
                      <router-link to="/login" class="btn btn-outline-success my-2 mr-2 my-sm-0"><i class="fas fa-sign-in-alt"></i> Đăng nhập</router-link>
                      <router-link to="/register" class="btn btn-outline-warning my-2 my-sm-0"><i class="fas fa-pen-square"></i> Đăng ký</router-link>
                    </div>
                    <div v-else>
                      <router-link to="/user" class="btn btn-info my-2 mr-2 my-sm-0" title="Thông tin tài khoản"><i class="fas fa-user-shield"></i> {{currentUser.name}}</router-link>
                      <button class="btn btn-danger my-2 my-sm-0" @click="logout"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</button>
                    </div>
                </form>
            </div>
        </nav>
    </div>
</template>
<script>
export default {
    name: 'home',
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        }
    },
    methods: {
        logout(){
            this.$store.commit('logout')
            this.$router.push('/')
        }
    }
}
</script>
