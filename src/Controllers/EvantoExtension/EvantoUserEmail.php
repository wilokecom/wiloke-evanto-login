<?php

namespace WilokeEvantoLogin\Controllers\EvantoExtension;

use WilokeEvantoLogin\Controllers\Controller;

/**
 * Class EvantoListPurchases
 * @package WilokeEvantoLogin\Controllers\EvantoExtension
 */
final class EvantoUserEmail extends Controller
{
    protected $endpoint = "https://api.envato.com/v1/market/private/user/email.json";
    
    /**
     * @return array|object
     */
    private function grabUserEmail()
    {
        return $this->grabPrivateUserDetails();
    }
    
    /**
     * @return array|object
     */
    public function get()
    {
        return $this->grabUserEmail()->email;
    }
}
