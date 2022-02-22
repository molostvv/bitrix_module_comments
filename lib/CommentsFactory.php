<?
/**
 * Фабрика объектов для комментариев
 */

namespace Demis\Comments;

class CommentsFactory{

	public static function getCommentsManager(){
		return new CommentsManager(static::getDataProvider(), static::getSocialAuth());
	}

	public static function getDataProvider(){
		return new GeneralDataProvider();
	}

	public static function getSocialAuth(){
		return new SocialAuth();
	}

}

?>