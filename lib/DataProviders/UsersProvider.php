<?
namespace Vspace\Comments\DataProviders;

use Vspace\Comments\FileManager;
use Vspace\Comments\Entities\UsersTable;

class UsersProvider{
    
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
                'SOCIAL_ID'         => $userSocialId, 
                'SOCIAL_PROVIDER'   => $userSocialType
            ),
            'limit' => 1
        );
        $oUsers = UsersTable::getList($params);

        if($arUsers = $oUsers->fetch()) return $arUsers;
        else return false;

    }

    /**
     * Добавления профиля пользователя
     * @param array $data
     * @return boolean
     * @throws \Exception
     */
    public function addSocialUser($data){

        $userData = array(
            'FIRSTNAME'             => $data['firstname'],
            'LASTNAME'              => !empty($data['lastname']) ? $data['lastname']: " ",
            'EMAIL'                 => !empty($data['email']) ? $data['email']: "  ",
            'PHOTO'                 => FileManager::saveRemoteFile($data['image']),
            'SOCIAL_ID'             => $data['id'],
            'SOCIAL_PROVIDER'       => $data['socialprovider'],
            'SOCIAL_PROFILE_URL'    => $data['profileURL']
        );

        $result = UsersTable::add($userData);

        if(!$result->isSuccess())
            throw new \Exception(implode("; ", $result->getErrorMessages()));

        $id = $result->getId();
        $userData['ID'] = $id;
        return $userData;
    }

}

?>