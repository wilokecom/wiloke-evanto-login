<?php

namespace WilokeEvantoLogin\Controllers;

/**
 * Class UserController
 * @package WilokeEvantoLogin\Controllers
 */
class UserController
{
    protected $email;
    protected $username;
    protected $password;
    protected $userId;
    
    /**
     * @param $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        
        return $this;
    }
    
    
    /**
     * @param $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        
        return $this;
    }
    
    /**
     * @return false|int
     */
    protected function isEmailExists()
    {
        return email_exists($this->email);
    }
    
    /**
     * @return false|int
     */
    protected function isUsernameExists()
    {
        return username_exists($this->username);
    }
    
    /**
     * @param array $aInfo
     *
     * @return $this
     * @throws \Exception
     */
    public function setEvantoClientIdAndToken(array $aInfo)
    {
        if (empty($aInfo['client_id']) || empty($aInfo['client_token'])) {
            throw new \Exception('Invalid client_id or client_token');
        }
        update_user_meta($this->userId, 'evanto_client_id_token', $aInfo);
        
        return $this;
    }
    
    /**
     * @param $purchaseCode
     *
     * @return $this
     * @throws \Exception
     */
    public function setEvantoPurchaseCode($purchaseCode)
    {
        if (empty($purchaseCode)) {
            throw new \Exception('Invalid Purchase code');
        }
        update_user_meta($this->userId, 'evanto_client_purchase_code', $purchaseCode);
        
        return $this;
    }
    
    /**
     * @param $supportedTimestamp
     *
     * @return $this
     * @throws \Exception
     */
    public function setSupportedUntil($supportedTimestamp)
    {
        if (empty($supportedTimestamp) || !is_numeric($supportedTimestamp)) {
            throw new \Exception('Invalid supported timestamp');
        }
        update_user_meta($this->userId, 'supported_until', $supportedTimestamp);
        
        return $this;
    }
    
    /**
     * @param $aInfo
     *
     * @return $this
     * @throws \Exception
     */
    public function setEvantoToken($aInfo)
    {
        if (empty($aInfo['refresh_token']) || empty($aInfo['access_token'])) {
            throw new \Exception('Invalid refresh_token or access_token');
        }
        update_user_meta($this->userId, 'evanto_client_token', $aInfo);
        
        return $this;
    }
}
