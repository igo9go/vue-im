<template>
    <transition name="fade">
        <div class="notice" v-bind:class="notice.type" v-if="notice.show">
            <p>{{ notice.msg }}</p>
        </div>
    </transition>
</template>


<script>
    import {mapGetters} from 'vuex';

    export default {
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'notice',
            ])
        }
    }
</script>


<style lang="less">
    .notice{
        width: 300px;
        height: 40px;
        line-height: 40px;
        position: absolute;
        top: 0%;
        left: 50%;
        margin-left: -50px;
        background: #00ff00;

        p {
            text-align: center;
        }
    }
    .success{
        color: #3c763d;
        background: #dff0d8;
        box-shadow: 0px 0px 10px #46b855;
    }
    .warning{
        color: #8a6d3b;
        background: #fcf8e3;
        box-shadow: 0px 0px 10px rgba(129, 148, 95, 0.71);
    }
    .error{
        color: #a94442;
        background: #f2dede;
        box-shadow: 0px 0px 10px #F77B6F;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-active {
        opacity: 0;
    }
</style>