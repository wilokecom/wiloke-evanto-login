<?php

namespace WilokeEvantoLogin\Controllers\EvantoExtension;

use WilokeEvantoLogin\Controllers\Controller;

/**
 * Class EvantoUserAccountDetail
 * @package WilokeEvantoLogin\Controllers\EvantoExtension
 */
final class EvantoUserAccountDetail extends Controller
{
    protected $endpoint = "https://api.envato.com/v1/market/private/user/account.json";
    private $userName;
    
    /**
     * @param $userName
     *
     * @return $this
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        
        return $this;
    }
    
    /**
     * @return array
     */
    private function grabUserInfo()
    {
        $this->endpoint = str_replace('{username}', $this->userName, $this->endpoint);
        return $this->grabPrivateUserDetails();
    }
    
    /**
     * @return array
     */
    public function get()
    {
        return $this->grabUserInfo();
    }
}
