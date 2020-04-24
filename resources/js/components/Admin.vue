<template>
    <div>
        <header-app class="mb-5"></header-app>
        <transition name="slide-fade">
            <router-view></router-view>
        </transition>
    </div>
</template>
<script>
import Header from './Header'
export default {
    name: 'admin',
    components: {
        'header-app': Header
    }
}
</script>
