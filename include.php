<?
use Bitrix\Main\Loader;

const VSPACE_COMMENTS_MODULE_ID = 'vspace.comments';

try{
	if(class_exists('Hybridauth\Hybridauth')){
		Loader::registerAutoLoadClasses(VSPACE_COMMENTS_MODULE_ID, array(
			'Vspace\Comments\DataProvider\GeneralDataProvider'	=> 'lib/DataProviders/GeneralDataProvider.php',
			'Vspace\Comments\SocialAuth'						=> 'lib/SocialAuth.php',
			'Vspace\Comments\CommentsFactory'					=> 'lib/CommentsFactory.php',
		    'Vspace\Comments\CommentsManager'					=> 'lib/CommentsManager.php',
		    'Vspace\Comments\FileManager'						=> 'lib/FileManager.php',
		));
	} else {
		throw new \Exception('Не подключена библиотека Hybridauth необходимая для работы модуля ' . VSPACE_COMMENTS_MODULE_ID);
	}
} catch (\Exception $ex) {
    echo $ex->getMessage();
    exit;
}

?>