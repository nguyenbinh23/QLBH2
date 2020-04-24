<template>
    <div>
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="md-col-8">
                    <h3>NOT ALLOWED</h3>
                    <p>Bạn không có đủ quyền hạn để vào đường dẫn này</p>
                    <router-link :to="{ name: 'home-page'}" class="btn btn-outline-info">Quay về trang chủ</router-link>
                </div>
            </div>
        </div>
    </div>
</template>
