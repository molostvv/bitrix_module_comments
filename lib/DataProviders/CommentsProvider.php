<?
namespace Vspace\Comments\DataProviders;

use Bitrix\Main\Type;
use Vspace\Comments\Entities\MessagesTable;

class CommentsProvider{
	
	/**
	 * Получение списка комментариев
	 * @return array
	 */
	public function getList($params = []){
        $oComments = MessagesTable::getList($params);
        $result = [];
        while ($arComments = $oComments->fetch())
        {
            $result[] = $this->extractFromDB($arComments);
        }
        return $result;
	}

	public function getCountComments(){
        global $DB;
        $oResult = $DB->Query('SELECT COUNT(*) as COUNT FROM `' . MessagesTable::getTableName() . '`');
        $arResult = $oResult->fetch();

        if( $arResult['COUNT'] > 0) {
            return $arResult['COUNT'];
        } return 0;
    }

	/**
	 * Конвертирует необходимые поля из формата БД Битрикса в нормальный php-шный
	 * @return array
	 */
	public function extractFromDB($arComments){
		if(!empty($arComments["DATE_CREATE"])){
			$arComments["DATE_CREATE"] = ($arComments["DATE_CREATE"])->format("d.m.Y H:i:s");
		}
		return $arComments;
	}

	/**
	 * Публикация комментария
	 * @param int $userId
	 * @param int $itemId
	 * @param array
	 */
	public function setComment($userId, $itemId, $message){

		$now = date('Y-m-d h:i:s', strtotime('now'));

		$messageData = array(
			'USER_ID' 	   	=> $userId, 
			'ITEM_ID'		=> $itemId,
			'DATE_CREATE'	=> new Type\DateTime($now, 'Y-m-d h:i:s'),
			'MESSAGE'		=> $message
		);

		$messageResult = MessagesTable::add($messageData);

		if ($messageResult->isSuccess()){
		    $messageResult = $messageResult->getData();
		    $messageResult = $this->extractFromDB($messageResult);
		} else {
			$messageResult = false;
		}

		return $messageResult;
	}
}

?>