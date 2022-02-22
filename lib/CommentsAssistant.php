<?
/**
 * Класс, отвечающий за выполнение операций сервиса
 */

namespace Demis\Comments;

use Hybridauth\Hybridauth;
use Hybridauth\Provider\Vkontakte;

class CommentsAssistant{

    /**
     * @var GeneralDataProvider
     */
    protected $generalDataProvider;

	public function __construct(GeneralDataProvider $generalDataProvider){
		$this->generalDataProvider = $generalDataProvider;
	}

	public function getComments($params = []){
		return $this->generalDataProvider->getComments($params);
	}

	public function setComment($userId, $itemId, $message){
		return $this->generalDataProvider->setComment($userId, $itemId, $message);
	}

	/**
	 * Авторизация пользователей в социальных сетях
	 * @param  array $config
	 * @return array
	 */
	public function auth($config){
		
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

		$userProfile = json_decode(json_encode($userProfile), true);

		return array(
			'id' 		=> $userProfile["identifier"],
			'firstname' => $userProfile["firstName"],
			'lastname' 	=> $userProfile["lastName"],
			'email' 	=> $userProfile["email"],
			'image' 	=> $userProfile["photoURL"],
		);
	}

	/**
	 * Добавления нового пользователя зарегисрированного через соц. сети
	 * @param arrray $param
	 * @return array
	 */
	public function addSocialUser($params){
		return $this->generalDataProvider->addSocialUser($params);
	}

	/**
	 * Проверка существования пользователя в БД
	 * @param  int  $userSocialId
	 * @param  string  $userSocialType
	 * @return boolean
	 */
	public function issetUserDB($userSocialId, $userSocialType){
		return $this->generalDataProvider->issetUserDB($userSocialId, $userSocialType);
	}
}

?>