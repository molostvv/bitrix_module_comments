<link rel="stylesheet" href="/local/templates/demis_edges/css/comments.min.css">
<?

include 'autoload.php';

use Hybridauth\Hybridauth;
use Hybridauth\Provider\Vkontakte;

$config = [
	'callback'  => 'http://www.demis.test.ru/local/modules/dms.comments/lib/Hybridauth/TEST.php', //Hybridauth\HttpClient\Util::getCurrentUrl()
	'keys' => [ 
		'id'     => '7166165',
		'secret' => '6GPzgJMbq9SLZFh9B6yL' 
	],
];


$adapter = new Vkontakte($config);

try {
    if (!$adapter->isConnected()) {
        $adapter->authenticate();
    }
    $userProfile = $adapter->getUserProfile();
}
catch(\Exception $e) {
    print $e->getMessage() ;
}


echo '<pre>';
	var_dump( $userProfile );
echo '</pre>';

?>

<!--
<div class="comments">
   <div class="container comments__container">
      <ul class="comments__list">
         <li>
            <article class="comment" tabindex="-1">
               <header class="comment-header">
                  <div>
                     <div class="width-auto">
                        <img class="comment-avatar" src="img/comments/avatar-01.png" width="30" height="30" alt="">
                     </div>
                     <div class="width-expand">
                        <h4 class="comment-author"><a href="#">Админ Админов</a></h4>
                     </div>
                     <div class="width-auto">
                        <p class="comment-meta"><a href="#"><span class="icon-calendar"></span>13.07.2020</a></p>
                     </div>
                  </div>
               </header>
               <div class="comment-body">
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
               </div>
               <footer class="comment-footer">
                  <div class="">
                     <a href="#"><span>Имя</span></a>
                     <a href="#"><span class="icon-quote"></span><span>Цитировать</span></a>
                     <a href="#"><span class="icon-view-alt"></span><span>Показать</span></a>
                     <a href="#"><span class="icon-delete"></span><span>Удалить</span></a>
                     <a href="#"><span class="comment-like"><span class="icon-heart"></span>5</span><span>Нравится</span></a>
                  </div>
               </footer>
            </article>
            <ul>
               <li>
                  <article class="comment" tabindex="-1">
                     <form class="comment-form">
                        <header class="comment-header">
                           <div>
                              <div class="width-auto">
                                 <img class="comment-avatar" src="img/comments/avatar-02.png" width="30" height="30" alt="">
                              </div>
                              <div class="width-expand">
                                 <h4 class="comment-author"><a href="#">Guest</a></h4>
                              </div>
                              <div class="width-auto">
                                 <p class="comment-meta"><a href="#"><span class="icon-calendar"></span>13.07.2020</a></p>
                              </div>
                           </div>
                        </header>
                        <div class="comment-body">
                           <textarea rows="1" cols="10" placeholder="Написать комментарий..."></textarea>
                        </div>
                        <div class="comment-footer">
                           <div>
                              <div><button class="btn btn__red" type="submit">Отправить</button></div>
                              <div class="comment-form-subscribe">
                                 <input type="checkbox" id="subscribe-comments" checked="checked"><label for="subscribe-comments">Подписаться на новые сообщения этой темы</label>
                              </div>
                           </div>
                           <div class="comment-form-auth">
                              <div>
                                 Для отправки сообщения авторизуйтесь <a class="icon-twitter-alt" href="#twitter"></a><a class="icon-facebook" href="#fb"></a><a class="icon-instagram" href="#insta"></a><a class="icon-vk2" href="#vk"></a>
                              </div>
                           </div>
                        </div>
                     </form>
                  </article>
               </li>
            </ul>
         </li>
      </ul>
   </div>
</div>
-->