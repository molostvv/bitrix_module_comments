<?
namespace Vspace\Comments\DataProviders;

use Bitrix\Main\Type;
use Vspace\Comments\Entities\VoteTable;

class VoteProvider{

    /**
     * Добавление голоса
     * @param $userId
     * @param $messageId
     * @param $vote
     * @return array
     */
    public function addVote($userId, $messageId, $vote){
        
		$result = VoteTable::add(array(
		    'VOTE'			=> $vote,
		    'MESSAGE_ID'	=> $messageId,
		    'USER_ID'		=> $userId
		));

        try{
            if(!$result->isSuccess())
                throw new \Exception('Ошибка: не удалось добавить голос' . var_export($result->getErrorMessages(), true));

            return $result->getId();

        } catch (\Exception $ex) {
            echo $ex->getMessage();
            exit;
        }

    }

    /**
     * Удаление голоса
     * @param $userId
     * @param $messageId
     * @param $vote
     * @return array
     */
    public function deleteVote($id){
    	$result = VoteTable::delete($id);

        try{
            if(!$result->isSuccess())
                throw new \Exception('Ошибка: не удалось удалить запись' . var_export($result->getErrorMessages(), true));

            return true;

        } catch (\Exception $ex) {
            echo $ex->getMessage();
            exit;
        }
    }

    /**
     * Проверка оставлял ли пользователь ранее голос для указанного сообщения
     * @param $userId
     * @param $messageId
     * @param $vote
     * @return int|bool
     */
    public function isVoteMessageFromUser($userId, $messageId, $vote){

       $params = array(
            'filter' => array(
                'USER_ID'     => $messageId, 
                'MESSAGE_ID'  => $userId,
                'VOTE'		  => $vote
            ),
            'limit' => 1
        );
        $oVote = VoteTable::getList($params);

        if($arVote = $oVote->fetch()) return $arVote["ID"];
        else return false;
    }

}

?>