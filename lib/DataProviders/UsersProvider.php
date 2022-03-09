<?
namespace Vspace\Comments\DataProviders;

require_once __DIR__ . "/UsersTable.php";

class UsersProvider{

    const AVATAR_DIR = '/upload/comments/';

    /**
     * Получение списка пользователей
     * @param $params
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
	public function getUsers($params){

        $oUsers = UsersTable::getList($params);

        $userList = array();

        while($arUsers = $oUsers->fetch()){
        	$userList[ $arUsers['ID'] ] = $arUsers;
        }

        return $userList;
	}

    /**
     * Ищет пользователя с указанным socialId и socialType
     * @param $userSocialId
     * @param $userSocialType
     * @return bool|array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
	public function findSocialUser($userSocialId, $userSocialType){

		$params = array(
			'filter' => array(
				'SOCIAL_ID'		=> $userSocialId, 
				'SOCIAL_TYPE'	=> $userSocialType
			),
			'limit' => 1
		);
        $oUsers = UsersTable::getList($params);

        if($arUsers = $oUsers->fetch()) return $arUsers;
        else return false;

	}

	private static function saveAvatar($avatarUrl){

        $imageId	= false;
    	$uploadPath = $_SERVER['DOCUMENT_ROOT'] . self::AVATAR_DIR;
    	$imageName  = basename( parse_url($avatarUrl)['path'] );
		$imageData  = file_get_contents($avatarUrl);

        try{
            if(!is_dir($uploadPath)){
                if(!mkdir($uploadPath, 0777)){
                    throw new \Exception('Ошибка: не удалось создать директорию для изображений');
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            exit;
        }

        $imageResult = file_put_contents($uploadPath . $imageName, $imageData);

        try{
            if(!($imageResult > 0))
                throw new \Exception('Ошибка: не удалось сохранить изображение');

            $arFile 			 = \CFile::MakeFileArray($uploadPath . $imageName);
            $arFile["MODULE_ID"] = "dms.comments";
            $imageId 			 = \CFile::SaveFile($arFile, $arFile["MODULE_ID"]);

        } catch (\Exception $ex) {
            echo $ex->getMessage();
            exit;
        }

        return $imageId;
    }

    /**
     * Добавления профиля пользователя
     * @param array $data
     * @return boolean
     * @throws \Exception
     */
    public function addSocialUser($data){

		$userData = array(
			'FIRSTNAME'		=> $data['firstname'],
			'LASTNAME'		=> $data['lastname'],
			'EMAIL'			=> $data['email'],
			'PHOTO'		  	=> self::saveAvatar($data['image']),
			'SOCIAL_ID'   	=> $data['id'],
			'SOCIAL_TYPE'	=> $data['socialtype']
		);

		$result = UsersTable::add($userData);

        try{
            if(!$result->isSuccess())
                throw new \Exception('Ошибка: не удалось добавить пользователя');

            $id = $result->getId();
            $userData['ID'] = $id;
            return $userData;

        } catch (\Exception $ex) {
            echo $ex->getMessage();
            exit;
        }
    }

}

?>