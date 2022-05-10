<?
namespace Vspace\Comments\DataProviders;

use Bitrix\Main\Type;
use Vspace\Comments\Entities\VoteTable;
use \Vspace\Comments\Enums\VoteTypes;

class VoteProvider{

    /**
     * Получение списка голосов
     * @param $params
     * @return array
     */
    public function getVote($params){

        $oVote = VoteTable::getList($params);

        $voteList = array();

        while($arVote = $oVote->fetch()){
            $voteList[ $arVote['ID'] ] = $arVote;
        }

        return $voteList;
    }

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

        if(!$result->isSuccess())
            throw new \Exception(implode("; ", $result->getErrorMessages()));

        return $result->getId();
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

        if(!$result->isSuccess())
            throw new \Exception(implode("; ", $result->getErrorMessages()));

        return true;
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

    /**
     * Подсчет количества лайков и дизлайков для каждого сообщения
     * @return array
     */
    public function getVoteCountForMessages(){
        global $DB;

        $likes    = 'CASE WHEN VOTE = "' . VoteTypes::LIKE['value'] . '" THEN 1 ELSE NULL END';
        $dislikes = 'CASE WHEN VOTE = "'. VoteTypes::DISLIKE['value'] .'" THEN 1 ELSE NULL END';

        $oVote = $DB->Query('SELECT COUNT(' . $likes . ') AS COUNT_LIKE, COUNT(' . $dislikes . ') AS COUNT_DISLIKE, MESSAGE_ID FROM ' . VoteTable::getTableName() . ' GROUP BY MESSAGE_ID');

        $arResult = [];
        while($arVote = $oVote->fetch()){
            $arResult[ $arVote["MESSAGE_ID"] ] = [
                'COUNT_LIKE'    => $arVote['COUNT_LIKE'],
                'COUNT_DISLIKE' => $arVote['COUNT_DISLIKE']
            ];
        }

        return $arResult;
    }

}

?>