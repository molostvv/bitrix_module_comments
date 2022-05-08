<div id="vcomments">
    <div class="comments">
        <div class="container comments__container">
            <ul class="comments__list" v-if="dataLoad">
                <li>
                    <div v-if="comments.length">
                        <transition-group name="list" tag="div">
                            <comment-item v-for="comment in comments" v-bind:comment="comment" v-bind:key="comment.ID"></comment-item>
                        </transition-group>
                    </div>
                    <div v-else>Комментарии отсутствуют</div>

                    <div class="comments__view-more" v-if="displayPaginate" v-on:click="showMoreComments">Показать еще</div>

                    <br>
                    <ul>
                        <li>
                            <article class="comment">
                                <form class="comment-form">
                                    <comment-post v-if="isAuth" v-bind:user="user" v-bind:pageId="pageId" v-on:update="updateComments" v-on:logout="logout = true"></comment-post>
                                    <comments-auth-btns v-bind:social="social" v-on:winclose="checkAuth" v-on:logout="checkAuth" v-else></comments-auth-btns>
                                </form>
                            </article>
                        </li>
                    </ul>
                </li>
            </ul>
            <div v-else>Данные загружаются ...</div>
        </div>
    </div>
</div>