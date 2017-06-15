<template>
    <div>
        <div class="user-text">
            <textarea name="" id="" placeholder="Press enter to send" v-model="content" v-on:keyup.enter="send"></textarea>
        </div>
        <div class="text-footer">
            <button v-on:click="send">发送</button>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        data () {
            return{
                content : ''
            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'currentUser',
                'currentSession',
                'conn'
            ])
        },
        methods : {
            send(e){
                let date = new Date().toLocaleString();
                let _this = this;
                let msg = {
                        from : _this.currentUser.id,
                        to : _this.currentSession.id,
                        msg : _this.content,
                        date : date
                    };

                if (_this.content !== '' ) {
                    _this.conn.send(JSON.stringify(msg));
                    _this.content = '';
                    msg.is_self = 1;
                    _this.$store.dispatch('addMessage', msg)
                }else{
                    _this.$store.dispatch('showNotice', ' 消息不能为空!','warning')
                }
                
            }
        },
    }
</script>


<style lang="less">
    .user-text{
        height: 120px;
        border-top: 1px solid #ddd;

        textarea{
            padding: 10px;
            resize: none;
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
        }
    }
    .text-footer{
        display: flex;
        padding: 10px 20px;
        background: #fff;
        justify-content: flex-end;
        align-items: flex-end;
        button{
            background: #fff;
            padding: 3px 20px;
            color: #222;
            border: 1px solid #c1c1c1;
            border-radius: 3px;
        }
        span{
            font-size: 14px;
            color: #999;
            margin-right: 10px;
        }
    }
</style>