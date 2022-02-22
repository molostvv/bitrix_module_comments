<? 
	if(!check_bitrix_sessid())
		return;
?>

<? echo CAdminMessage::ShowNote('Модуль ' . $this->MODULE_ID . ' установлен'); ?>