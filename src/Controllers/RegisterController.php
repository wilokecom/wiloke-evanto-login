<?php

namespace WilokeEvantoLogin\Controllers;

/**
 * Class RegisterController
 * @package WilokeEvantoLogin\Controllers
 */
class RegisterController extends UserController
{
    /**
     * @param $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        
        return $this;
    }
    
    /**
     * @return mixed|string
     */
    private function generateUniqueUserName()
    {
        
        static $i;
        if (null === $i) {
            $i = 1;
        } else {
            $i++;
        }
        
        if (!$this->isEmailExists()) {
            return $this->username;
        }
        
        $newUserName = sprintf('%s-%s', $this->username, $i);
        
        if (!username_exists($newUserName)) {
            return $newUserName;
        } else {
            return call_user_func([$this, 'generateUniqueUserName'], $this->username);
        }
    }
    
    /**
     * @return \WP_Error|\WP_User
     */
    public function register()
    {
        $username = $this->generateUniqueUserName();
        
        $aUserData = [
            'user_login' => $username,
            'user_pass'  => wp_generate_password(),
            'user_email' => $this->email,
            'role'       => 'subscriber'
        ];
        
        // Inserting new user to the db
        wp_insert_user($aUserData);
        
        $creds                  = [];
        $creds['user_login']    = $username;
        $creds['user_password'] = $aUserData['user_pass'];
        $creds['remember']      = true;
        
        return wp_signon($creds, is_ssl());
    }
}
