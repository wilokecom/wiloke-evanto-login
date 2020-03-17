<?php

namespace WilokeEvantoLogin\Controllers\EvantoExtension;

use WilokeEvantoLogin\Controllers\Controller;

/**
 * Class EvantoPurchaseCode
 * @package WilokeEvantoLogin\Controllers\EvantoExtension
 */
final class EvantoVerifyPurchaseCode extends Controller
{
    private $purchaseCode;
    protected $endpoint = "https://api.envato.com/v3/market/author/sale";
    private $aItemIds = [];
    private $aMatched = [];
    
    /**
     * @param $code
     *
     * @return $this
     */
    public function setPurchaseCode($code)
    {
        $this->purchaseCode = $code;
        
        return $this;
    }
    
    /**
     * @param $itemId https://themeforest.net/item/foodhub-recipes-wordpress-theme/25164442 The id is 25164442
     *
     * @return $this
     */
    public function setItemIds($itemId)
    {
        $aItemIds = is_array($itemId) ? $itemId : explode(',', $itemId);
        $aItemIds = array_map(function ($item) {
            return trim($item);
        }, $aItemIds);
        
        $this->aItemIds = array_merge($aItemIds, $this->aItemIds);
        
        return $this;
    }
    
    /**
     * Find all purchased license that matched this items. The results got from EvantoListPurchases
     *
     * @param $aPurchasedItems
     *
     * @return $this
     * @throws \Exception
     */
    private function isMatchedItemIds(array $aPurchasedItems)
    {
        if (empty($this->aItemIds)) {
            throw new \Exception('The item id is required');
        }
        
        foreach ($aPurchasedItems as $oItem) {
            if (in_array($oItem->item->id, $this->aItemIds)) {
                $this->aMatched[] = $oItem;
            }
        }
        
        return $this;
    }
    
    /**
     * @param array $aPurchasedItems
     *
     * @return array
     * @throws \Exception
     */
    public function getMatchedItemIds(array $aPurchasedItems)
    {
        return $this->isMatchedItemIds($aPurchasedItems)->aMatched;
    }
    
    /**
     * @param array $aPurchasedItems
     *
     * @return array|mixed
     * @throws \Exception
     */
    public function getLastPurchased(array $aPurchasedItems)
    {
        $this->isMatchedItemIds($aPurchasedItems);
        if (empty($this->aMatched)) {
            return [];
        }
        
        //        uasort($this->aMatched, function ($oItem1, $oItem2) {
        //            return strtotime($oItem1->supported_until) < strtotime($oItem2->supported_until);
        //        });
        
        return $this->aMatched[0];
    }
    
    /**
     *
     * @return mixed|null
     */
    private function verify()
    {
        $oResponse = $this->http($this->endpoint, [
            'code' => $this->purchaseCode
        ]);
        
        if ($this->isHttpError($oResponse)) {
            return [
                'status' => 'error',
                'msg'    => 'Invalid Purchase code'
            ];
        }
        
        return [
            'status'   => 'success',
            'username' => $oResponse->buyer
        ];
    }
    
    /**
     * @param $purchaseCode
     *
     * @return mixed|null
     */
    public function isValid($purchaseCode)
    {
        $this->purchaseCode = $purchaseCode;
        
        return $this->verify();
    }
}
