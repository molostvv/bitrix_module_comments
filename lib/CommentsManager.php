<?
/**
 * Менеджер комментариев, реализует основные сценарии работы с модулем
 */

namespace Vspace\Comments;
use Vspace\Comments\DataProviders\GeneralDataProvider;

class CommentsManager{
	
    private const PROVIDER_NAME_COOKIE_KEY  = 'vspace_provider_name';
    private const USER_ID_COOKIE_KEY        = 'vspace_user_id';
    private const HASH_COOKIE_KEY           = 'vspace_hash';

	/**
	 * @var $generalDataProvider
	 */
    protected $generalDataProvider;

    /**
     * @var $socialAuth
     */
    protected $socialAuth;

    public function __construct(GeneralDataProvider $generalDataProvider, SocialAuth $socialAuth){
    	$this->generalDataProvider = $generalDataProvider;
        $this->socialAuth          = $socialAuth;
    }

    /**
     * Получает список комментариев в формате bitrix`a
     * @param  array $params
     * @return array
     */
    public function getComments($params = []){

        $commentsList = $this->generalDataProvider->getComments($params);

        // Формируем фильтр для получения списка пользователей
        $paramsUsers['filter']['ID'] = array_column($commentsList, 'USER_ID');
        $usersList = $this->generalDataProvider->getUsers($paramsUsers);
        
        // Получем количество лайков для каждого сообщения
        $arMessagesVotes = $this->generalDataProvider->getVoteCountForMessages();

        foreach ($commentsList as $commentId => $comment) {
            if(array_key_exists($comment['USER_ID'], $usersList)){
                $commentsList[$commentId]['USER'] = $usersList[ $comment['USER_ID'] ];
            }

            if(array_key_exists($commentId, $arMessagesVotes)){
               $commentsList[$commentId] = array_merge($commentsList[$commentId], $arMessagesVotes[$commentId]);
            }
        }

        // Формируем фильтр для получения списка голосов для сообщений для указанного пользователя
        $paramsVote['filter']['MESSAGE_ID'] = array_column($commentsList, 'ID');
        $paramsVote['filter']['USER_ID']    = 1; // todo: Заменить на id текущего пользователя
        $voteList = $this->generalDataProvider->getVote($paramsVote);

        foreach ($voteList as $vote) {
            if(array_key_exists($vote['MESSAGE_ID'], $commentsList)){
                $commentsList[$vote['MESSAGE_ID']]['VOTE_CURRENT_USER'][$vote['VOTE']] = true;
            } 
        }

        return $commentsList;
    }

    /**
     * Получение количества комментариев
     * @return int
     */
    public function getCountComments(){
        return $this->generalDataProvider->getCountComments();
    }

    /**
     * Получения списка комментариев для пользователя
     * @param  int $userId
     * @return array
     */
    public function getCommentsForUser($userId){
        $params['filter'] = array('USER_ID' => $userId);
        return $this->generalDataProvider->getComments($params);
    }
    
    /**
     * Получение списка комментариев для элемента
     * @param  int $itemId
     * @return array
     */
    public function getCommentsForItem($itemId){
        $params['filter'] = array('ITEM_ID' => $itemId);
        return $this->generalDataProvider->getComments($params);
    }

    /**
     * Публикация комментария
     * @param int $userId
     * @param int $itemId
     * @param array
     */
    public function setComment($userId, $itemId, $message){
        return $this->generalDataProvider->setComment($userId, $itemId, $message);
    }

    /**
     * Авторизация пользователей в социальных сетях
     * @param  array $socialType
     * @return array
     */
    public function auth($providerName){

        $socialUserData = $this->socialAuth->auth($providerName);
        $cacheLifeTime  = time() + 50000;
        $hash           = md5($socialUserData["id"] . ':' . $providerName);

        setcookie(self::USER_ID_COOKIE_KEY, $socialUserData["id"], $cacheLifeTime);
        setcookie(self::PROVIDER_NAME_COOKIE_KEY, $providerName, $cacheLifeTime);
        setcookie(self::HASH_COOKIE_KEY, $hash, $cacheLifeTime);

        return $socialUserData;
    }

    /**
     * Проверка автооризации пользователя
     * @return array
     */
    public function isAuth(){
        $isAuth = false;
        $hash = md5($_COOKIE[self::USER_ID_COOKIE_KEY] . ':' . $_COOKIE[self::PROVIDER_NAME_COOKIE_KEY]);
        if(strcasecmp($hash, $_COOKIE[self::HASH_COOKIE_KEY]) === 0)
            $isAuth = true;

        return $isAuth;
    }

    /**
     *  Возвращает id авторизованного пользователя
     *  @return bool|int
     */
    public function getUserId(){
        if(!empty($_COOKIE[self::USER_ID_COOKIE_KEY])){
            return $this->generalDataProvider->findSocialUser($_COOKIE[self::USER_ID_COOKIE_KEY], $_COOKIE[self::PROVIDER_NAME_COOKIE_KEY]);
        } else return false;
    }

    /**
     *  Получает данные пользователя по его id
     *  @param  int $userId
     *  @return bool|array
     */
    public function getUserById($userId){
        
        $usersList = array();
        $firstItem = array();

        $params['filter'] = array(
            'ID' => $userId
        ); 

        $usersList = $this->generalDataProvider->getUsers($params);
        $firstItem = array_shift($usersList);

        if(!empty($firstItem)){
            return $firstItem;
        } else return false;

    }

    /**
     * Ищет пользователя с указанным socialId и socialType
     * @param  int  $userSocialId
     * @param  string  $userSocialType
     * @return boolean|array
     */
    public function findSocialUser($userSocialId, $userSocialType){
       return $this->generalDataProvider->findSocialUser($userSocialId, $userSocialType);
    }

    /**
     * Добавления профиля пользователя
     * @param  array  $data
     * @return bool|int
     */
    public function addSocialUser($data){
        return $this->generalDataProvider->addSocialUser($data);
    }

    /**
     * Получение списка всех активных провайдеров для авторизации
     * @return array
     */
    public function getSocialAuthActive(){

        $providersData = [];
        $configForProviders = $this->socialAuth->getProviders();

        foreach($configForProviders as $providerName => $config){
            $providersData[$providerName] = [
                'icon' => $config["icon"]
            ];
        }

        return $providersData;
    }

    /**
    * Разлогинить пользователя
    * @return void
    */
    public function logout(){
        setcookie(self::USER_ID_COOKIE_KEY, "", time() - 1);
        setcookie(self::PROVIDER_NAME_COOKIE_KEY, "", time() - 1);
        setcookie(self::HASH_COOKIE_KEY, "", time() - 1);
    }


    /**
    * Добавить/удалить лайк/дизлайк у сообщения от пользователя
    * @param  int  $userId
    * @param  int  $messageId
    * @param  string  $vote
    * @return bool
    */
    public function toggleLike($userId, $messageId, $vote){
        $voteId = $this->generalDataProvider->isVoteMessageFromUser($userId, $messageId, $vote);
        
        if($voteId){
            $result = $this->generalDataProvider->deleteVote($voteId);
        } else {
            $result = $this->generalDataProvider->addVote($userId, $messageId, $vote);
        }
        
        return $result;
    }

}

?>