<template>
    <div>
         <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <router-link to="/"><a class="navbar-brand" href="#">TUANMINHTECH</a></router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="btn-group dropleft" v-if="currentUser !== null">
                    <button type="button" class="btn btn-primary my-2 mr-2 my-sm-0 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{currentUser.name}} <i class="fa fa-user" aria-hidden="true" v-if="currentUser.quyenhan !== 'admin'" ></i><i v-else class="fa fa-user-shield" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu">
                        <router-link class="dropdown-item" v-if="currentUser.quyenhan === 'admin' && $route.path !== '/admin'" to="/admin"><i class="fa fa-spin fa-cog" aria-hidden="true"></i> Trang quản trị </router-link>
                        <button class="dropdown-item" @click="logout"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</button>
                    </div>
                </div>
                <div class="btn-group dropleft" v-else>
                    <router-link to="/login" type="button" class="btn btn-primary my-2 mr-2 my-sm-0">
                       <i class="fa fa-sign-in" aria-hidden="true"></i> Đăng nhập
                    </router-link>
                    <router-link to="/register" type="button" class="btn btn-warning my-2 mr-2 my-sm-0">
                       <i class="fas fa-sign-in-alt"></i> Đăng ký
                    </router-link>
                </div>
            </div>
        </nav>
    </div>
</template>
<script>
export default {
    name: 'home',
    created(){
        if(this.currentUser == null){
            this.$router.push('/login')
        }
    },
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
