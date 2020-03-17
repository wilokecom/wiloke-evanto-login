<?php

namespace WilokeEvantoLogin\Controllers\EvantoExtension;

use WilokeEvantoLogin\Controllers\Controller;

/**
 * Class EvantoListPurchases
 * @package WilokeEvantoLogin\Controllers\EvantoExtension
 */
final class EvantoListPurchases extends Controller
{
    protected $endpoint = "https://api.envato.com/v3/market/buyer/list-purchases";
    private $aConfigurations = [
        'page' => 1
    ];
    
    /**
     * @param $filterBy : wordpress-themes, wordpress-plugins
     *
     * @return $this
     */
    public function setFilterBy($filterBy)
    {
        $this->aConfigurations['filter_by'] = $filterBy;
        
        return $this;
    }
    
    /**
     * @param $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->aConfigurations['page'] = $page;
        
        return $this;
    }
    
    /**
     * @return array|object
     */
    private function handleGrabPurchases()
    {
        if (!empty($this->aConfigurations)) {
            $this->endpoint = add_query_arg(
                $this->aConfigurations,
                $this->endpoint
            );
        }
        
        return $this->grabPrivateUserDetails();
    }
    
    /**
     * @return array|object
     */
    public function get()
    {
        return $this->handleGrabPurchases();
    }
}
