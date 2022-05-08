<template>
    <div>
        <header class="comment-header">
            <div>
                <div class="width-auto">
                    <img class="comment-avatar" v-bind:src="user.PHOTO_PATH" width="30" height="30" alt="">
                </div>
                <div class="width-expand">
                    <h4 class="comment-author">
                        <a href="#">{{user.FULLNAME}}</a> ( {{user.SOCIAL_PROVIDER}})
                        <a v-on:click="logout" class="comment-logout">logout</a>
                    </h4>
                </div>
                <div class="width-auto">
                    <p class="comment-meta"><a href="#">&nbsp;</a></p>
                </div>
            </div>
        </header>
        <div class="comment-body">
            <input type="hidden" name="user_id" v-bind:value="user.ID">
            <input type="hidden" name="page_id" value="1">
            <textarea rows="1" cols="10" name="message" placeholder="Написать комментарий..." v-model="message"></textarea>
        </div>
        <div class="comment-footer">
            <div>
                <div v-bind:class="{ preloader: preloader }">
                    <button class="btn btn__red" type="button" v-on:click="postComment">Отправить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: Object,
            pageId: Number
        },
        data() {
            return {
                message: '',
                preloader: false
            }
        },
        methods: {
            getUrlAddComment(){
                return location.pathname + '?action=addComment&message=' + this.message + '&user_id=' + this.user.ID + '&item_id=' + this.pageId;
            },
            getUrlLogOut(){
                return location.pathname + '?action=logout';
            },

            postComment: async function() {
                this.preloader = true;
                let response = await fetch(this.getUrlAddComment());
                let resp = await response.text();
                this.preloader = false;
                this.$emit('update');
            },

            logout: async function(){
                this.preloader = true;
                let response = await fetch(this.getUrlLogOut());
                this.preloader = false;
                this.$emit('logout');    
            }
        }
    }
</script>