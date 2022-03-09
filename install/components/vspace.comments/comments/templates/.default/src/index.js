import Vue from 'vue'

import CommentItem from './components/CommentItem'
import CommentPost from './components/CommentPost'
import CommentsAuthBtns from './components/CommentsAuthBtns'

console.log("module:comments");

const getListUrl = location.pathname + '?action=getList';

let vcomments = new Vue({
    el: '#vcomments',
    components: { CommentItem, CommentPost, CommentsAuthBtns },
    data: {
    	comments: {},
        user: {},
        social: {},
        pageId: false,

        count: null,
        offset: 0,
        nPageSize: null
    },
    computed: {
        displayPaginate: function(){
            return this.comments.length && this.count > this.offset + this.nPageSize;
        },
        dataLoad: function(){
            return this.pageId ? true : false;
        }
    },
    methods: {
        getData: async function(urlParams = ''){
            let url = getListUrl;

            if(urlParams){
                url = getListUrl + '&' + urlParams;
            }

            let response = await fetch(url);
            return await response.json();
        },
        updateComments: async function () {
            let params = 'offset=0&' + 'limit=' + (this.offset + this.nPageSize);

            let data = await this.getData(params);
            this.comments = data['COMMENTS'];

            /* pagin */
            this.count     = data['COMMENTS_COUNT'];
            this.nPageSize = data['PAGE_LIMIT'];
        },
        showMoreComments: async function (){
            this.offset += this.nPageSize;

            if(this.offset > this.count)
                this.offset = this.count;

            let params = 'offset=' + this.offset;
            let data = await this.getData(params);
            let temp = this.comments;
            this.comments = temp.concat( data['COMMENTS'] );
        },
        checkAuth: async function (){
            let data = await this.getData();
            this.user     = data['USER'];
        }
    },
    created: async function() {
        // Получаем данные
        let data      = await this.getData();
        this.comments = data['COMMENTS'];
        this.user     = data['USER'];
        this.social   = data['SOCIAL'];
        this.pageId   = data['PAGE_ID'];

        /* pagin */
        this.count     = data['COMMENTS_COUNT'];
        this.nPageSize = data['PAGE_LIMIT'];
    }
});