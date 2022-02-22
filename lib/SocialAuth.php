<?
namespace Demis\Comments;

use Hybridauth\Hybridauth;
use Hybridauth\Provider\Vkontakte;
use Hybridauth\Provider\Facebook;
use Hybridauth\Provider\Twitter;
use Hybridauth\Provider\Instagram;
use \Bitrix\Main\Application;
use \Hybridauth\HttpClient\Util;

class SocialAuth{

    public function getSocialConfig(){
        $options = [];

        $options['twitter'] = [
            'id'        => \COption::GetOptionString('dms.comments', 'twitter_id'),
            'secret'    => \COption::GetOptionString('dms.comments', 'twitter_secret'),
            'icon'      => 'icon-twitter-alt'
        ];

        $options['facebook'] = [
            'id'        => \COption::GetOptionString('dms.comments', 'facebook_id'),
            'secret'    => \COption::GetOptionString('dms.comments', 'facebook_secret'),
            'icon'      => 'icon-facebook'
        ];

        $options['instagram'] = [
            'id'        => \COption::GetOptionString('dms.comments', 'instagram_id'),
            'secret'    => \COption::GetOptionString('dms.comments', 'instagram_secret'),
            'icon'      => 'icon-instagram'
        ];

        $options['vk'] = [
            'id'        => \COption::GetOptionString('dms.comments', 'vk_id'),
            'secret'    => \COption::GetOptionString('dms.comments', 'vk_secret'),
            'icon'      => 'icon-vk2'
        ];

        return $options;
    }

	public function auth($socialType){

        global $APPLICATION;
        $options = $this->getSocialConfig();

        try{
            if(array_key_exists($socialType, $options)){
                $keys = $options[$socialType];
                if(empty($keys['id']) || empty($keys['secret'])){
                    throw new \Exception('Ошибка: не заполнены в настройках данные приложения');
                }
            } else throw new \Exception('Ошибка: не найдены данные приложения для текущего провайдера');
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            exit;
        }

        //$arrUrlFrag = parse_url($APPLICATION->GetCurPageParam());
        // $callbackUrl = Util::getCurrentUrl() . '?' . $arrUrlFrag['query'];
        $callbackUrl = Util::getCurrentUrl() . '?action=auth&provider=' . $socialType;


        $config = [
            'callback'  => $callbackUrl,
            'keys' => [ 
                'id'     => $keys['id'],
                'secret' => $keys['secret'], 
            ],
        ];

        switch ($socialType){
            case 'twitter':
                $adapter = new Facebook($config);
                break;
            case 'facebook':
                $adapter = new Twitter($config);
                break;
            case 'instagram':
                $adapter = new Instagram($config);
                break;

            case 'vk':
                $adapter = new Vkontakte($config);
                break;
        }

        try {
            if (!$adapter->isConnected()) {
                $adapter->authenticate();
            }
            $userProfile = $adapter->getUserProfile();
        }
        catch(\Exception $e) {
            print $e->getMessage();
            exit;
        }

        $userProfile = json_decode(json_encode($userProfile), true);

        return array(
            'id'        => $userProfile["identifier"],
            'firstname' => $userProfile["firstName"],
            'lastname'  => $userProfile["lastName"],
            'email'     => $userProfile["email"],
            'image'     => $userProfile["photoURL"],
            'socialtype'=> $socialType,
            'profileURL'=>$userProfile["profileURL"]
        );

        
	}

}

?>