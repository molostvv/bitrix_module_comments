<?
/**
 * Менеджер комментариев, реализует основные сценарии работы с модулем
 */

namespace Vspace\Comments;
use Vspace\Comments\DataProviders\GeneralDataProvider;

class CommentsManager{
	
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

        $usersList      = array();
        $usersListId    = array();
        $commentsList   = $this->generalDataProvider->getComments($params);

        foreach ($commentsList as $comment) {
            $usersListId[] = $comment["USER_ID"];
        }

        $paramsUsers['filter'] = array(
            'ID' => $usersListId
        );

        $usersList = $this->generalDataProvider->getUsers($paramsUsers);
        
        foreach ($commentsList as $commentId => $comment) {
            if(array_key_exists($comment['USER_ID'], $usersList)){
                $commentsList[$commentId]['USER'] = $usersList[ $comment['USER_ID'] ];
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
    public function auth($socialType){
        $socialUserData = $this->socialAuth->auth($socialType);
        $cacheLifeTime  = time() + 50000;
        $socialType     = "vk";
        $hash           = md5($socialUserData["id"] . ':' . $socialType);

        setcookie("dms_user_id", $socialUserData["id"], $cacheLifeTime);
        setcookie("dms_social_type", $socialType, $cacheLifeTime);
        setcookie("dms_hash", $hash, $cacheLifeTime);

        return $socialUserData;
    }

    /**
     * Проверка автооризации пользователя
     * @return array
     */
    public function isAuth(){
        $isAuth = false;
        $hash = md5($_COOKIE['dms_user_id'] . ':' . $_COOKIE['dms_social_type']);
        if(strcasecmp($hash, $_COOKIE['dms_hash']) === 0)
            $isAuth = true;

        return $isAuth;
    }

    /**
     *  Возвращает id авторизованного пользователя
     *  @return bool|int
     */
    public function getUserId(){
        if(!empty($_COOKIE['dms_user_id'])){
            return $this->generalDataProvider->findSocialUser($_COOKIE['dms_user_id'], $_COOKIE['dms_social_type']);
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
     * Получения списка всех активных провайдеров для авторизации
     * @return array
     */
    public function getSocialAuthActive(){
        $socialData = [];
        $config = $this->socialAuth->getSocialConfig();

        foreach($config as $socialName => $authData){
            if(!empty($authData['id']) && !empty($authData['secret'])){
                unset($authData['id']);
                unset($authData['secret']);
                $socialData[$socialName] = $authData;
            }
        }

        return $socialData;
    }

}

?>