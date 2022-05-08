<template>
	<article class="comment" tabindex="-1">
		<header class="comment-header">
			<div>
				<div class="width-auto">
					<img class="comment-avatar" v-bind:src="comment.USER.PHOTO_PATH" width="30" height="30" alt="">
				</div>
				<div class="width-expand">
					<h4 class="comment-author"><a href="#">{{comment.USER.FULLNAME}}</a> ({{comment.USER.SOCIAL_PROVIDER}})</h4>
				</div>
				<div class="width-auto">
					<p class="comment-meta">
						<a href="#">
							<span class="icon-calendar"></span>
							{{comment.DATE_CREATE_FORMAT}}
						</a>
					</p>
				</div>
			</div>
		</header>
		<div class="comment-body">
			<p>{{comment.MESSAGE}}</p>
		</div>
		<div class="comment-message-footer">
			<a v-on:click="vote('LIKE')">like</a>
			<a v-on:click="vote('DISLIKE')">dislike</a>
		</div>
	</article>
</template>

<script>
	export default {
		data() {
			return {
				focused: false
			}
		},
		props: {
			comment: Object,
			user: Object
		},
		methods: {
            getUrlVote(vote){
                return location.pathname + '?action=vote&user_id=' + this.user.ID + '&comment_id=' + this.comment.ID + '&vote=' + vote;
            },
            vote: async function(vote){
                let response = await fetch(this.getUrlVote(vote));
                console.log(response);   
            }
		}
	}
</script>