<?
namespace Vspace\Comments;

/**
 * 	Менеджер файлов
 */
class FileManager
{
	
	const FILES_DIR = '/upload/' . VSPACE_COMMENTS_MODULE_ID . '/';

	/*
	*	Сохраняет удаленный файл локально
	*/
	static public function saveRemoteFile($remoteUrl){ 

		$filesDir 		 = $_SERVER['DOCUMENT_ROOT'] . self::FILES_DIR;

		$fileName  		 = basename( parse_url($remoteUrl)['path'] );
		$fileBinaryData  = file_get_contents($remoteUrl);

        $fileId	= false;
    	
    	// Создаем директорию, если отсутствует
        if(!is_dir($filesDir)){
            if(!mkdir($filesDir, 0777)){
                throw new \Exception('Ошибка: не удалось создать директорию для файлов');
            }
        }

        $fileSaveResult = file_put_contents($filesDir . $fileName, $fileBinaryData);

        if(!($fileSaveResult > 0))
            throw new \Exception('Ошибка: не удалось сохранить изображение');

        $arFile 			 = \CFile::MakeFileArray($filesDir . $fileName);
        $fileSaveId 		 = \CFile::SaveFile($arFile, ["MODULE_ID" => VSPACE_COMMENTS_MODULE_ID]);

        return $fileSaveId;
	}
}
?>