<?
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

Class Vspace_comments extends CModule
{

    var $MODULE_ID = "vspace.comments";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $MODULE_FOLDER = "local";

	public function __construct()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        if(strpos($mystring, $this->MODULE_FOLDER) !== false)
            $this->MODULE_FOLDER = "bitrix";


        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME        = Loc::getMessage('MODULE_NAME') . ' ' . $this->MODULE_ID;
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION') . ' ' . $this->MODULE_ID;
	}

    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/" . $this->MODULE_FOLDER . "/modules/" . $this->MODULE_ID . "/install/components",
                     $_SERVER["DOCUMENT_ROOT"] . "/" . $this->MODULE_FOLDER . "/components", true, true);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/" . $this->MODULE_FOLDER . "/components/" . $this->MODULE_ID);
        return true;
    }

    function installDB(){
        global $APPLICATION, $DB;

        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . '/' . $this->MODULE_FOLDER . '/modules/' . $this->MODULE_ID . '/install/db/install.sql');

        if($this->errors !== false){
            $APPLICATION->ThrowException(implode("", $this->errors));
            return false;
        }

        return;
    }

    function uninstallDB(){
        global $APPLICATION, $DB;

        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . '/' . $this->MODULE_FOLDER . '/modules/' . $this->MODULE_ID . '/install/db/uninstall.sql');
        
        if($this->errors !== false){
            $APPLICATION->ThrowException(implode("", $this->errors));
            return false;
        }

        return;
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallFiles();
        $this->installDB();
        RegisterModule($this->MODULE_ID);
 
        $pageTitle = Loc::getMessage("VSPACE_MODULE_INSTALL") . ' ' . $this->MODULE_ID;
        $APPLICATION->IncludeAdminFile($pageTitle, $_SERVER["DOCUMENT_ROOT"] . '/' . $this->MODULE_FOLDER . '/modules/' . $this->MODULE_ID . '/install/step.php');
    }

    function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallFiles();
        $this->uninstallDB();
        UnRegisterModule($this->MODULE_ID);

        $pageTitle = Loc::getMessage("VSPACE_MODULE_UNINSTALL") . ' ' . $this->MODULE_ID;
        $APPLICATION->IncludeAdminFile($pageTitle, $_SERVER["DOCUMENT_ROOT"] . '/' . $this->MODULE_FOLDER . '/modules/' . $this->MODULE_ID . '/install/unstep.php');
    }


}

?>