<?php

namespace WilokeEvantoLogin\Controllers;

use WilokeEvantoLogin\Controllers\EvantoExtension\EvantoListPurchases;
use WilokeEvantoLogin\Controllers\EvantoExtension\EvantoUserEmail;
use WilokeEvantoLogin\Controllers\EvantoExtension\EvantoVerifyPurchaseCode;
use WilokeEvantoLogin\Helpers\Session\Message;

/**
 * Class EvantoAuthentication
 * @package WilokeEvantoLogin\Controllers
 */
class EvantoAuthentication extends Controller
{
    private $aTokenInfo = [];
    /**
     * @var \WilokeEvantoLogin\Helpers\Session\Message
     */
    private $oMessage;
    protected $accessToken;
    private $aEvantoClientToken;
    
    public function __construct()
    {
        add_action('init', [$this, 'sessionInit'], 1);
        add_shortcode('wiloke_login_evanto_login', [$this, 'renderEvantoLogin']);
        add_action('init', [$this, 'handleLoginWithEvanto']);
        //        add_action('init', [$this, 'test']);
    }
    
    public function sessionInit()
    {
        $this->oMessage = new Message();
    }
    
    /**
     * @param $code
     *
     * @return object
     */
    private function grabEvantoToken($code)
    {
        return $this->http(
            'https://api.envato.com/token',
            [
                'grant_type' => 'authorization_code',
                'code'       => $code
            ]
        );
    }
    
    /**
     * @param int $page
     *
     * @return array|mixed
     */
    protected function getPurchaseCodes($page = 1)
    {
        $oEvantoUserAccountDetail = new EvantoListPurchases();
        $oResponse                = $oEvantoUserAccountDetail->setFilterBy($this->getOptionField('evanto_item_types'))
                                                             ->setAccessToken($this->accessToken)
                                                             ->setPage($page)
                                                             ->get()
        ;
        
        if ($page !== 1 && ($this->isHttpError($oResponse) || empty($oResponse))) {
            return [];
        }
        
        if (!$this->isHttpError($oResponse)) {
            $oEvantoVerifyPurchaseCode = new EvantoVerifyPurchaseCode();
            try {
                $aLastPurchased = $oEvantoVerifyPurchaseCode->setAccessToken($this->accessToken)
                                                            ->setItemIds($this->getOptionField('evanto_item_ids'))
                                                            ->getLastPurchased($oResponse->results)
                ;
                
                if (empty($aLastPurchased)) {
                    return call_user_func([$this, 'getPurchaseCodes'], $page + 1);
                }
                
                return $aLastPurchased;
            } catch (\Exception $e) {
                echo $e->getMessage();
                die();
            }
        }
        
        return [];
    }
    
    /**
     * @return object
     */
    private function getUserInfo()
    {
        $aInfo             = [];
        $oUserEmail        = new EvantoUserEmail();
        $aInfo['email']    = $oUserEmail->setAccessToken($this->accessToken)->get();
        $oUserUsername     = new EvantoUserEmail();
        $aInfo['username'] = $oUserUsername->setAccessToken($this->accessToken)->get();
        
        return (object)$aInfo;
    }
    
    /**
     * @return array|bool
     * @throws \Exception
     */
    public function handleLoginWithEvanto()
    {
        if (!isset($_GET['login']) || $_GET['login'] !== 'evanto') {
            return false;
        }
        
        $oResponse = $this->grabEvantoToken($_GET['code']);
        if ($this->isHttpError($oResponse)) {
            $this->oMessage->set(
                'login_error',
                esc_html__('We could not find your account on Evanto', 'wiloke-evanto-login')
            );
            
            return false;
        }
        
        $this->aEvantoClientToken = get_object_vars($oResponse);
        $this->accessToken        = $oResponse->access_token;
        
        $oPurchaseCode = $this->getPurchaseCodes(1);
   
        if (empty($oPurchaseCode)) {
            $this->oMessage->set(
                'login_error',
                esc_html__('It seems you purchased the item on another account', 'wiloke-evanto-login')
            );
        } else {
            $oUserLogin = new LoginController();
            $redirectTo = $this->getOptionField('evanto_login_redirect_to', home_url('/'));
            $oUserInfo  = $this->getUserInfo();
            
            if ($oUserLogin->canLogin($oUserInfo->email)) {
                $oUserLogin->setEvantoToken($this->aEvantoClientToken)->setEvantoPurchaseCode($oPurchaseCode->code)
                           ->setSupportedUntil(strtotime($oPurchaseCode->supported_until))
                ;
                wp_safe_redirect($redirectTo);
                exit;
            }
            
            $oRegister = new RegisterController();
            $oUser     = $oRegister->setUsername($oUserInfo->username)->setEmail($oUserInfo->email)->register();
            if (empty($oUser) || is_wp_error($oUser)) {
                $this->oMessage->set(
                    'login_error',
                    esc_html__('Failed! We could not register your account', 'wiloke-evanto-login')
                );
                
                return false;
            }
            
            if ($oUserLogin->canLogin($oUserInfo->email)) {
                $oUserLogin->setEvantoToken($this->aEvantoClientToken)
                           ->setEvantoPurchaseCode($oPurchaseCode->code)
                           ->setSupportedUntil(strtotime($oPurchaseCode->supported_until))
                ;
                wp_safe_redirect($redirectTo);
                exit;
            }
            
            return true;
        }
    }
    
    public function test()
    {
        //        $oResponse = $this->grabEvantoToken($_GET['code']);
        //        if ($this->isHttpError($oResponse)) {
        //            return [
        //                'status' => 'error',
        //                'msg'    => 'Something went error'
        //            ];
        //        }
        
        $this->accessToken = 'QDrkYIjAR24G8qNA09UDqQY4DZPDV8UF';
        
        $aPurchaseCode = $this->getPurchaseCodes(1);
        
        if (empty($aPurchaseCode)) {
            $this->oMessage->set(
                'login_error',
                esc_html__('It seems you purchased the item on another account', 'wiloke-evanto-login')
            );
        } else {
            $oUserInfo = $this->getUserInfo();
        }
        die;
    }
    
    /**
     * @return false|string
     */
    public function renderEvantoLogin()
    {
        ob_start();
        ?>
        <a href="<?php echo $this->apiUrl(); ?>">Evanto Login</a>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
    
    /**
     * @return string
     */
    private function apiUrl()
    {
        $this->getOption();
        
        return add_query_arg(
            [
                'response_type' => 'code',
                'client_id'     => $this->aOptions['evanto_client_id'],
                'redirect_uri'  => urlencode($this->aOptions['evanto_redirect_url'])
            ],
            'https://api.envato.com/authorization'
        );
    }
}
