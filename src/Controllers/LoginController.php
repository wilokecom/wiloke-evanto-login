<?php

namespace WilokeEvantoLogin\Controllers;

/**
 * Class LoginController
 * @package WilokeEvantoLogin\Controllers
 */
class LoginController extends UserController
{
    /**
     * @param $email
     *
     * @return bool
     */
    public function canLogin($email)
    {
        $this->setEmail($email);
        if ($this->isEmailExists()) {
            $oUser = get_user_by('email', $this->email);
            wp_set_current_user($oUser->ID, $oUser->user_login);
            wp_set_auth_cookie($oUser->ID, true, false);
            
            return true;
        }
        
        return false;
    }
}
