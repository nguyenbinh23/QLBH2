<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header bg-success text-white text-center"><h4>Hàng hóa</h4></div>
                    <div class="card-body bg-dark text-white">
                        <transition name="slide-fade">
                            <router-view></router-view>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'products-main',
}
</script>
