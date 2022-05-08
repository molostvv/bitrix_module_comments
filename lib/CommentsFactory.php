<?
/**
 * Фабрика объектов для комментариев
 */

namespace Vspace\Comments;
use Vspace\Comments\DataProviders\GeneralDataProvider;
use Vspace\Comments\DataProviders\CommentsProvider;
use Vspace\Comments\DataProviders\UsersProvider;
use Vspace\Comments\DataProviders\VoteProvider;

class CommentsFactory{

	public static function getCommentsManager(){
		return new CommentsManager(static::getDataProvider(), static::getSocialAuth());
	}

	public static function getDataProvider(){
		return new GeneralDataProvider( new CommentsProvider(), new UsersProvider(), new VoteProvider());
	}

	public static function getSocialAuth(){
		return new SocialAuth();
	}

}

?>