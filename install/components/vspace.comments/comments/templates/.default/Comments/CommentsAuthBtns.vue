<template>
    <div class="comment-form-auth">
        <div>
            Для отправки сообщения авторизуйтесь
            <a v-for="(item, authProvider) in social" v-bind:class="item.icon" v-on:click="socialAuth(authProvider)"></a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            social: Object
        },
        data() {
            return {
            }
        },
        methods: {
            socialAuth: function(authProvider){
                var self = this;
                var url = location.pathname + '?action=auth&provider=' + authProvider;

                var screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
                    screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
                    outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
                    outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
                    width = 670,
                    height = 300,
                    left = parseInt(screenX + ((outerWidth - width) / 2), 10),
                    top = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                    features = ( 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top );

                let winAuth = window.open(url, 'Auth', features);

                var timer = setInterval(function(){
                    if(winAuth.closed){
                        self.$emit('winclose');
                        clearInterval(timer);
                    }
                }, 600);

                return false;

            }
        }
    }
</script>