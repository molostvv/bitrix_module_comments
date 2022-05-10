<?
/**
 * Основной класс провайдера данных
 */
namespace Vspace\Comments\DataProviders;


class GeneralDataProvider{

	/**
	 * @var MessagesProvider
	 */
	private $_messagesProvider;

	/**
	 * @var UsersProvider
	 */
	private $_usersProvider;

	public function __construct($commentsProvider, $usersProvider, $voteProvider){
		$this->_commentsProvider = $commentsProvider;
		$this->_usersProvider 	 = $usersProvider;
		$this->_voteProvider 	 = $voteProvider;
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

    /**
    *	Добавление голоса
    */
    public function addVote($userId, $messageId, $vote){
    	return $this->_voteProvider->addVote($userId, $messageId, $vote);
    }

    /**
    *	Удаление голоса
    */
    public function deleteVote($id){
    	return $this->_voteProvider->deleteVote($id);
    }

    /**
    *	Получить голос для сообщения от указаного пользователя
    */
    public function isVoteMessageFromUser($userId, $messageId, $vote){
    	return $this->_voteProvider->isVoteMessageFromUser($userId, $messageId, $vote);
    }

    /**
    *	Получение списка голосов
    */
    public function getVote($params = []){
    	return $this->_voteProvider->getVote($params);
    }

    /**
    *	Получение количества голосов для каждого сообщения
    */
    public function getVoteCountForMessages(){
    	return $this->_voteProvider->getVoteCountForMessages();
    }
}


?>