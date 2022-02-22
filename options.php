<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

// получаем идентификатор модуля
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] != '' ? $request['mid'] : $request['id']);
// подключаем наш модуль
Loader::includeModule($module_id);

/*
 * Параметры модуля со значениями по умолчанию
 */
$aTabs = array(
    array(
        /*
         * Вкладка «Основные настройки»
         */
        'DIV'     => 'edit1',
        'TAB'     => Loc::getMessage('DMS_COMMENTS_OPTIONS_TAB_GENERAL'),
        'TITLE'   => Loc::getMessage('DMS_COMMENTS_OPTIONS_TAB_GENERAL'),
        'OPTIONS' => array(
            // twitter
            Loc::getMessage('DMS_COMMENTS_OPTIONS_TAB_TWITTER'),
            array(
                'twitter_id',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_TWITTER_ID'),
                '',
                array('text', 25)
            ),
            array(
                'twitter_secret',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_TWITTER_SECRET'),
                '',
                array('text', 25)
            ),
            // facebook
            Loc::getMessage('DMS_COMMENTS_OPTIONS_TAB_FACEBOOK'),
            array(
                'facebook_id',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_FACEBOOK_ID'),
                '',
                array('text', 25)
            ),
            array(
                'facebook_secret',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_FACEBOOK_SECRET'),
                '',
                array('text', 25)
            ),
            // instagram
            Loc::getMessage('DMS_COMMENTS_OPTIONS_TAB_INSTAGRAM'),
            array(
                'instagram_id',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_INSTAGRAM_ID'),
                '',
                array('text', 25)
            ),
            array(
                'instagram_secret',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_INSTAGRAM_SECRET'),
                '',
                array('text', 25)
            ),
            // vk
            Loc::getMessage('DMS_COMMENTS_OPTIONS_TAB_VK'),
            array(
                'vk_id',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_VK_ID'),
                '',
                array('text', 25)
            ),
            array(
                'vk_secret',
                Loc::getMessage('DMS_COMMENTS_OPTIONS_VK_SECRET'),
                '',
                array('text', 25)
            ),
        )
    )
);

/*
 * Создаем форму для редактирвания параметров модуля
 */
$tabControl = new CAdminTabControl(
    'tabControl',
    $aTabs
);

$tabControl->begin();
?>
<form action="<?= $APPLICATION->getCurPage(); ?>?mid=<?=$module_id; ?>&lang=<?= LANGUAGE_ID; ?>" method="post">
    <?= bitrix_sessid_post(); ?>
    <?php
    foreach ($aTabs as $aTab) { // цикл по вкладкам
        if ($aTab['OPTIONS']) {
            $tabControl->beginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }
    $tabControl->buttons();
    ?>
    <input type="submit" name="apply" 
           value="<?= Loc::GetMessage('DMS_COMMENTS_OPTIONS_INPUT_APPLY'); ?>" class="adm-btn-save" />
</form>

<?php
$tabControl->end();

/*
 * Обрабатываем данные после отправки формы
 */
if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) { // цикл по вкладкам
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) { // если это название секции
                continue;
            }
            if ($arOption['note']) { // если это примечание
                continue;
            }
            if ($request['apply']) { // сохраняем введенные настройки
                $optionValue = $request->getPost($arOption[0]);
                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
            }
        }
    }

    LocalRedirect($APPLICATION->getCurPage().'?mid='.$module_id.'&lang='.LANGUAGE_ID);

}
?>