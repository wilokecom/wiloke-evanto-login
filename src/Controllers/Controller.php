<?php

namespace WilokeEvantoLogin\Controllers;

/**
 * Class Controller
 * @package WilokeEvantoLogin\Controllers
 */
abstract class Controller
{
    protected $optionKey = 'wiloke_evanto_settings';
    protected $aSettings;
    protected $aOptions;
    protected $accessToken;
    protected $endpoint;
    
    /**
     * @return mixed|void
     */
    protected function getOption()
    {
        if (!empty($this->aOptions)) {
            return $this->aOptions;
        }
        
        $this->aOptions = get_option($this->optionKey);
    }
    
    /**
     * @param $field
     * @param $default
     *
     * @return string
     */
    protected function getOptionField($field, $default = '')
    {
        $this->getOption();
        if (isset($this->aOptions[$field])) {
            return $this->aOptions[$field];
        }
        
        return $default;
    }
    
    protected function getConfig()
    {
        $this->aSettings = include WILOKE_EVANTO_PLUGIN_DIR.'configs/evanto-settings.php';
    }
    
    /**
     * @param $accessToken
     *
     * @return $this
     */
    protected function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        
        return $this;
    }
    
    protected function getAccessToken()
    {
        return $this->accessToken;
    }
    
    /**
     * @return mixed|null
     */
    protected function grabPrivateUserDetails()
    {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
        if (!empty($this->getAccessToken())) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$this->getAccessToken()]);
        }
        
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($result);
    }
    
    /**
     * @param $url
     * @param $aBody
     *
     * @return object
     */
    protected function http($url, $aBody)
    {
        $this->getOption();
        $aBody['client_id']     = $this->aOptions['evanto_client_id'];
        $aBody['client_secret'] = $this->aOptions['evanto_client_secret'];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
        if (!empty($this->accessToken)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$this->accessToken]);
        }
        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aBody));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($result);
    }
    
    /**
     * @param $oResponse
     *
     * @return bool
     */
    protected function isHttpError($oResponse)
    {
        if (isset($oResponse->error)) {
            return true;
        }
        
        return false;
    }
}
