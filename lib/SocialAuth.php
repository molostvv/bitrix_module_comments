<?
namespace Vspace\Comments;

use \Bitrix\Main\Application;
use \Hybridauth\HttpClient\Util;

class SocialAuth{

    public function getProviders(){

        $options = [];
        $providers = [];

        $options['Twitter'] = [
            'id'        => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'twitter_id'),
            'secret'    => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'twitter_secret'),
            'icon'      => 'icon-twitter-alt'
        ];

        $options['Facebook'] = [
            'id'        => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'facebook_id'),
            'secret'    => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'facebook_secret'),
            'icon'      => 'icon-facebook'
        ];

        $options['Instagram'] = [
            'id'        => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'instagram_id'),
            'secret'    => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'instagram_secret'),
            'icon'      => 'icon-instagram'
        ];

        $options['Vkontakte'] = [
            'id'        => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'vk_id'),
            'secret'    => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'vk_secret'),
            'icon'      => 'icon-vk2'
        ];
  
        $options['Telegram'] = [
            'id'        => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'telegram_id'),
            'secret'    => \COption::GetOptionString(VSPACE_COMMENTS_MODULE_ID, 'telegram_secret'),
            'icon'      => 'icon-telegram'
        ];

        foreach($options as $providerClass => $config){
            if(class_exists('Hybridauth\Provider\\' . $providerClass) && !empty($config['id']) && !empty($config['secret'])){
                $providers[$providerClass] = $config;
            }
        }


        return $providers;
    }

	public function auth($providerName){

        global $APPLICATION;
        $providers = $this->getProviders();

        if(!array_key_exists($providerName, $providers))
            throw new \Exception('Ошибка: переданный провайдер отсутствует в списке доступных');


        $callbackUrl = Util::getCurrentUrl() . '?action=auth&provider=' . $providerName;

        $config = [
            'callback'  => $callbackUrl,
            'keys' => [ 
                'id'     => $providers[$providerName]['id'],
                'secret' => $providers[$providerName]['secret']
            ],
        ];

        $providerClassName = '\Hybridauth\Provider\\' . $providerName;
        $adapter = new $providerClassName($config);

        if (!$adapter->isConnected()) {
            $adapter->authenticate();
        }
        $userProfile = $adapter->getUserProfile();
 

        $userProfile = json_decode(json_encode($userProfile), true);

        return array(
            'id'             => $userProfile["identifier"],
            'firstname'      => $userProfile["firstName"],
            'lastname'       => $userProfile["lastName"],
            'email'          => $userProfile["email"],
            'image'          => $userProfile["photoURL"],
            'socialprovider' => $providerName,
            'profileURL'     => $userProfile["profileURL"]
        );

        
	}

}

?>