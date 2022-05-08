<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Vspace\Comments\CommentsFactory;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

class CommentsClassComponent extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams) {

        if(empty($arParams["DATE_FORMAT"]))
            $arParams["DATE_FORMAT"] = "d.m.Y H:i:s";

        if(empty($arParams["AVATAR_SIZE"]))
            $arParams["AVATAR_SIZE"] = array(30, 30);

        if(empty($arParams["LIMIT"]))
            $arParams["LIMIT"] = 5;

        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        if (!Loader::includeModule("vspace.comments")) {
            throw new Exception(Loc::getMessage("ERROR_LOAD_DEPENDENCIES"));
        }

        // Авторизация пользователей через соц. сети
        if ($request->get("action") == "auth" && !empty($request->get("provider"))) {
            $this->authorization();
        }

        // Обработка ajax запросов
        if ($request->get("action")) {
            $this->processAjax();
        }

        $this->arResult = $this->getInitialData();
        $this->includeComponentTemplate();
    }

    public function authorization(){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $commentsManager = CommentsFactory::getCommentsManager();
        $request         = \Bitrix\Main\Context::getCurrent()->getRequest();
        $authProvider    = $request->get("provider");

        if( $socialUserData = $commentsManager->auth($authProvider) ){
            $userData = $commentsManager->findSocialUser($socialUserData["id"], $authProvider);
            // Если пользователя нет, добавлем в базу данных
            if($userData === false){
                $userData = $commentsManager->addSocialUser($socialUserData);
            }

            echo "<script type='text/javascript'> window.opener.test = '" . json_encode($userData) . "'; window.close(); </script>";
            die();

        } else {
            throw new Exception('Authorization error!!!');
        }
    }

    public function processAjax(){
        global $APPLICATION;
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        $APPLICATION->RestartBuffer();
        header("Content-Type: application/json");
        $result = null;
        switch ($request->get("action")) {
            case "getList":
                $result = $this->getInitialData();
                break;
            case "addComment":
                // todo: Проверка на авторизацию
                $result = $this->addComment();
                break;
            case "removeComment":
                // todo: Проверка на авторизацию
                //$result = $this->removeComment();
                break;
            case "changeComment":
                // todo: Проверка на авторизацию
                //$result = $this->changeComment();
                break;
            case "logout":
                $result = $this->logout();
                break;
            case "vote":
                $result = $this->vote();
                break;
        }
        echo json_encode($result);
        die();
    }

    public function addComment(){
        global $APPLICATION;
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $userId  = $request->get("user_id");
        $itemId  = $request->get("item_id");
        $message = $request->get("message");
        $commentsManager = CommentsFactory::getCommentsManager();
        return $commentsManager->setComment($userId, $itemId, $message);
    }

    public function vote(){
        global $APPLICATION;
        $request    = \Bitrix\Main\Context::getCurrent()->getRequest();
        $userId     = $request->get("user_id");
        $messageId  = $request->get("comment_id");
        $vote       = $request->get("vote");

        $commentsManager = CommentsFactory::getCommentsManager();
        $commentsManager->toggleLike($userId, $messageId, $vote);
    }

    /*
    *  Разлогинивание авторизованного пользователя
    */
    public function logout(){
        $commentsManager = CommentsFactory::getCommentsManager();
        $commentsManager->logout();
    }

    public function getInitialData()
    {
        global $APPLICATION;
        $commentsManager = CommentsFactory::getCommentsManager();
        $userData        = [];

        $params['limit'] = $this->arParams["LIMIT"];
        $params['order'] = ['DATE_CREATE' => 'DESC'];

        if(!empty($_GET['offset'])){
            $params['offset'] = $_GET['offset'];
        }

        if(!empty($_GET['limit'])){
            $params['limit'] = $_GET['limit'];
        }

        // Получение списка комментариев
        $arComments      = $commentsManager->getComments($params);

        foreach ($arComments as $key => $comment) {
            $arComments[$key]["DATE_CREATE_FORMAT"] = FormatDate($this->arParams["DATE_FORMAT"], MakeTimeStamp($arComments[$key]["DATE_CREATE"]));
            $arComments[$key]["USER"]["FULLNAME"]   = $arComments[$key]["USER"]["FIRSTNAME"] . ' ' . $arComments[$key]["USER"]["LASTNAME"];
            $arImage = CFile::ResizeImageGet($comment['USER']['PHOTO'], $this->arParams["AVATAR_SIZE"], BX_RESIZE_IMAGE_EXACT, true);
            $arComments[$key]["USER"]["PHOTO_PATH"] = $arImage['src'];
        }

        // Получаем данные пользователя, если он авторизован
        if( $commentsManager->isAuth() ){
            $userId   = $commentsManager->getUserId();
            $userData = $commentsManager->getUserById($userId);

            $userData["FULLNAME"]   = $userData["FIRSTNAME"] . ' ' . $userData["LASTNAME"];
            $arImage = CFile::ResizeImageGet($userData['PHOTO'], $this->arParams["AVATAR_SIZE"], BX_RESIZE_IMAGE_EXACT, true);
            $userData["PHOTO_PATH"] = $arImage['src'];
        }

        // Получаем список активных провайдеров соц. сетей
        $socialAuthActive = $commentsManager->getSocialAuthActive();

        // Получаем количество комментариев
        $commentsCount    = $commentsManager->getCountComments();

        return array(
            'COMMENTS'       => $arComments,
            'COMMENTS_COUNT' => $commentsCount,
            'PAGE_LIMIT'     => $this->arParams["LIMIT"],
            'USER'           => $userData,
            'SOCIAL'         => $socialAuthActive,
            'PAGE_ID'        => 1
        );
    }
}