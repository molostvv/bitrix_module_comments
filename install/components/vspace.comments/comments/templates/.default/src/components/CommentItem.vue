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
	</article>
</template>

<script>
	export default {
		props: {
			comment: Object
		},
		data() {
			return {
				focused: false
			}
		},
	}
</script>