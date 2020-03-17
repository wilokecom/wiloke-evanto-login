<?php

namespace WilokeEvantoLogin\Controllers\EvantoExtension;

use WilokeEvantoLogin\Controllers\Controller;

/**
 * Class EvantoListPurchases
 * @package WilokeEvantoLogin\Controllers\EvantoExtension
 */
final class EvantoUserUsername extends Controller
{
    protected $endpoint = "https://api.envato.com/v1/market/private/user/username.json";
    
    /**
     * @return array|object
     */
    private function grabUserUsername()
    {
        return $this->grabPrivateUserDetails();
    }
    
    /**
     * @return array|object
     */
    public function get()
    {
        return $this->grabUserUsername()->username;
    }
}
