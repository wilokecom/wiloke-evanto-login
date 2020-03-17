<?php

namespace WilokeEvantoLogin\Helpers\Session;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Messsage
 * @package WilokeEvantoLogin\Helpers\Session
 */
class Message
{
    private $session;
    
    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }
    
    /**
     * @param       $key
     * @param       $msg
     */
    public function set($key, $msg)
    {
        $this->session->set($key, $msg);
    }
    
    /**
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public function get($key, $default)
    {
        return $this->session->get($key, $default);
    }
}
