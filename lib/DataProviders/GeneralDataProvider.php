<?
/**
 * Основной класс провайдера данных
 */
namespace Demis\Comments;

require_once __DIR__ . "/CommentsProvider.php";
require_once __DIR__ . "/UsersProvider.php";

class GeneralDataProvider{

	/**
	 * @var MessagesProvider
	 */
	private $_messagesProvider;

	/**
	 * @var UsersProvider
	 */
	private $_usersProvider;

	public function __construct(){
		$this->_commentsProvider = new CommentsProvider();
		$this->_usersProvider 	 = new UsersProvider();
	}

	public function getUsers($params){
		return $this->_usersProvider->getUsers($params);
	}

	public function getComments($params = []){
		return $this->_commentsProvider->getList($params);
	}

    /**
     * Получение количества комментариев
     * @return int
     */
	public function getCountComments(){
	    return $this->_commentsProvider->getCountComments();
    }

	/**
	 * Публикация комментария
	 * @param int $userId
	 * @param int $itemId
	 * @param array
	 */
	public function setComment($userId, $itemId, $message){
		return $this->_commentsProvider->setComment($userId, $itemId, $message);
	}

	/**
	 * Проверка существования пользователя в БД
	 * @param  int $userSocialId
	 * @param  string $userSocialType
	 * @return bool
	 */
	public function findSocialUser($userSocialId, $userSocialType){
		return $this->_usersProvider->findSocialUser($userSocialId, $userSocialType);
	}

    /**
     * Добавления профиля пользователя
     * @param  array  $data
     * @return boolean
     */
    public function addSocialUser($data){
        return $this->_usersProvider->addSocialUser($data);
    }

}


?>