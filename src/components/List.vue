<template>
    <div class="list">
        <ul>
            <li v-for="user in users" v-bind:key="user.id" v-bind:id="user.id"
                v-on:click="changeSession(user.id)">
                <img v-bind:src="user.avatar" v-bind:alt="user.name">
                <p>{{ user.nickname }} <span v-if="user.id == 0">({{ currentCount }})</span></p>
                <div v-bind:class="[ user.has_message ? 'dot' : '' ]"></div>
            </li>
        </ul>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'currentCount',
                'users',
                // ...
            ])
        },
        methods: {
            changeSession(userId){
                if (typeof userId == 'number') {
                    this.$store.dispatch('selectSession', userId)
                    this.$store.dispatch('setHasMessageStatus', userId, false)
                }
            }
        }
    }
</script>

<style lang="less">
    .list {
        height: 479px;
        overflow-y: scroll;
        overflow-x: hidden;

    &
    ::-webkit-scrollbar-button {
        display: none;
    }

    &
    ::-webkit-scrollbar {
        width: 8px;
        background-color: #4d4d4d;
    }

    /*定义滚动条轨道 内阴影+圆角*/
    &
    ::-webkit-scrollbar-track {
        background-color: #2e3238;
    }

    /*定义滑块 内阴影+圆角*/
    &
    ::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.1);
    }

    ul {
        margin: 0;
        padding: 0;
    }

    li {
        display: flex;
        flex-direction: row;
        align-items: center;
        height: 60px;
        cursor: pointer;
        border-bottom: 1px solid #292c33;
        padding: 15px;

    &
    :hover {
        background: rgba(255, 255, 255, 0.03);
    }

    &
    .active {
        background: rgba(255, 255, 255, 0.1);
    }

    img {
        width: 30px;
        height: 30px;
    }

    p {
        margin-left: 15px;
        font-size: 16px;
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        align-self: center;
        margin-left: 10px;
        background: #ff0000;
    }

    }
    }
</style>