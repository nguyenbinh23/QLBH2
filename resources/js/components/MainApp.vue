<template>
    <div class="container-fluid">
    <vue-progress-bar></vue-progress-bar>
        <transition name="slide-fade">
            <router-view></router-view>
        </transition>
        <go-top :size="30" :bg-color="color"></go-top>
    </div>
</template>

<script>
import GoTop from '@inotom/vue-go-top'
    export default {
        name: 'main-app',
        components: {
            'go-top': GoTop
        },
        data(){
            return {
                color: '#00FF7F'
            }
        },
    }
</script>
<style>
    .slide-fade-enter-active {
        transition: all .2s ease;
        opacity: 60;
    }
    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateX(10px);
        opacity: 0;
    }

    .view-enter-active, .view-leave-active {
    transition: opacity 0.2s ease-in-out;
    transform: 0.2 ease;
    }
    .view-enter-active {
        transition-delay: 0.2s;
    }
    .view-enter, .view-leave-to {
        opacity: 0;
    }
    .view-enter-to, .view-leave {
        opacity: 1;
        color: #f34949;
    }
</style>
