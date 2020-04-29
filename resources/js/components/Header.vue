<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <router-link to="/"><a class="navbar-brand" href="#">TUANMINHTECH</a></router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <template v-if="currentUser && currentUser.quyenhan === 'admin'">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active ml-2">
                            <router-link to="/admin/orders/new"><a class="nav-link active btn btn-primary" href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i> Lập hóa đơn</a></router-link>
                        </li>
                        <li class="nav-item dropdown active ml-2">
                            <a class="nav-link dropdown-toggle btn btn-outline-success" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-clipboard-list"></i> Hóa Đơn</a>
                            <div class="dropdown-menu">
                                <router-link to="/admin/orders"><a class="dropdown-item" href="#"><i class="fa fa-list" aria-hidden="true"></i> Danh sách hóa đơn</a></router-link>
                                <router-link to="/admin/orders/new"><a class="dropdown-item" href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i> Lập hóa đơn</a></router-link>
                            </div>
                        </li>
                        <li class="nav-item dropdown active ml-2">
                            <a class="nav-link dropdown-toggle btn btn-outline-danger" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bar-chart" aria-hidden="true"></i> Thống Kê</a>
                            <div class="dropdown-menu">
                                <router-link to="/admin/statistic" ><a class="dropdown-item" href="#"><i class="fa fa-line-chart"  aria-hidden="true"></i> Thông kê lãi suất</a></router-link>
                            </div>
                        </li>
                        <li class="nav-item dropdown active ml-2">
                            <a class="nav-link dropdown-toggle btn btn-outline-warning" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-box-open    "></i> Hàng Hóa</a>
                            <div class="dropdown-menu">

                                <router-link to="/admin/products"><a class="dropdown-item" href="#"><i class="fa fa-list" aria-hidden="true"></i> Danh sách hàng hóa</a></router-link>
                                <router-link to="/admin/products/new"><a class="dropdown-item" href="#"><i class="fa fa-plus" aria-hidden="true"></i> Thêm hàng hóa</a></router-link>
                                <router-link to="/admin/products/import"><a class="dropdown-item" href="#"><i class="fa fa-file-excel mr-1"></i> Thêm hàng hóa (EXCEL)</a></router-link>
                                <div class="dropdown-divider"></div>
                                <router-link to="/admin/categories"><a class="dropdown-item" href="#"><i class="fa fa-list-alt" aria-hidden="true"></i>  Thể loại hàng hóa</a></router-link>
                                <router-link to="/admin/units"><a class="dropdown-item" href="#"><i class="fa fa-balance-scale" aria-hidden="true"></i> Đơn vị hàng hóa</a></router-link>
                            </div>
                        </li>
                        <li class="nav-item dropdown active ml-2">
                            <a class="nav-link dropdown-toggle btn btn-outline-info" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> Khách Hàng</a>
                            <div class="dropdown-menu">
                                <router-link to="/admin/customers"><a class="dropdown-item" href="#"><i class="fas fa-users"></i> Danh sách khách hàng</a></router-link>
                                <router-link to="/admin/customers/new"><a class="dropdown-item" href="#"><i class="fas fa-user-plus"></i> Thêm khách hàng</a></router-link>
                            </div>
                        </li>
                    </ul>
                </template>
                <form class="form-inline my-2 my-lg-0 ">
                    <div v-if="!currentUser">
                      <router-link to="/login" class="btn btn-outline-success my-2 mr-2 my-sm-0"><i class="fas fa-sign-in-alt"></i> Đăng nhập</router-link>
                      <router-link to="/register" class="btn btn-outline-warning my-2 my-sm-0"><i class="fas fa-pen-square"></i> Đăng ký</router-link>
                    </div>


                    <div v-else>
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-primary my-2 mr-2 my-sm-0 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{currentUser.name}} <i class="fa fa-user" aria-hidden="true" v-if="currentUser.quyenhan !== 'admin'" ></i><i v-else class="fa fa-user-shield" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu">
                                <router-link class="dropdown-item" v-if="currentUser.quyenhan === 'admin' && $route.path !== '/admin'" to="/admin"><i class="fa fa-spin fa-cog" aria-hidden="true"></i> Trang quản trị </router-link>
                                <button class="dropdown-item" @click="logout"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'app-header',
    data(){
        return {

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
            this.$router.push('/login')
        }
    }
}
</script>
