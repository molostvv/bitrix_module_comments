<?
use Bitrix\Main\Loader;

include 'lib/Hybridauth/autoload.php';

Loader::registerAutoLoadClasses('vspace.comments', array(
	'Demis\Comments\GeneralDataProvider' => 'lib/DataProviders/GeneralDataProvider.php',
	'Demis\Comments\SocialAuth' => 'lib/SocialAuth.php',
	'Demis\Comments\CommentsFactory' => 'lib/CommentsFactory.php',
    'Demis\Comments\CommentsManager' => 'lib/CommentsManager.php',
));

?>