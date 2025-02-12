<?php
/**
 * Copyright © 2015 Pay.nl All rights reserved.
 */

namespace Paynl\Payment\Model;

use Magento\Store\Model\Store;

/**
 * Description of Config
 *
 * @author Andy Pieters <andy@pay.nl>
 */
class Config
{

    /** @var  Store */
    private $store;

    public function __construct(
        Store $store
    ) {
        $this->store = $store;
    }

    /**
     * @param Store $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }

    public function isSkipFraudDetection()
    {
        return $this->store->getConfig('payment/paynl/skip_fraud_detection') == 1;
    }

    public function isTestMode()
    {
        return $this->store->getConfig('payment/paynl/testmode') == 1;
    }
    public function isSendDiscountTax(){
        return $this->store->getConfig('payment/paynl/discount_tax') == 1;
    }
    public function isNeverCancel()
    {
        return $this->store->getConfig('payment/paynl/never_cancel') == 1;
    }

    public function isAlwaysBaseCurrency()
    {
        return $this->store->getConfig('payment/paynl/always_base_currency') == 1;
    }

    public function getLanguage()
    {
        $language = $this->store->getConfig('payment/paynl/language');

        return $language ? $language : 'nl'; //default nl
    }

    public function getPaymentOptionId($methodCode)
    {
        return $this->store->getConfig('payment/' . $methodCode . '/payment_option_id');
    }

    public function getPendingStatus($methodCode){
        return $this->store->getConfig('payment/' . $methodCode . '/order_status');
    }
    public function getAuthorizedStatus($methodCode){
        return $this->store->getConfig('payment/' . $methodCode . '/order_status_authorized');
    }

    public function getPaidStatus($methodCode){
        return $this->store->getConfig('payment/' . $methodCode . '/order_status_processing');
    }

    /**
     * @param $methodCode string
     * @return string
     */
    public function getSuccessPage($methodCode){
        $success_page = $this->store->getConfig('payment/' . $methodCode . '/custom_success_page');
        if(empty($success_page)) $success_page = 'checkout/onepage/success';

        return $success_page;
    }

    /**
     * Configures the sdk with the API token and serviceId
     *
     * @return bool TRUE when config loaded, FALSE when the apitoken or serviceId are empty
     */
    public function configureSDK()
    {
        $apiToken  = $this->getApiToken();
        $serviceId = $this->getServiceId();
        $tokencode = $this->getTokencode();

        if(! empty($tokencode)) {
            \Paynl\Config::setTokenCode($tokencode);
        }

        if ( ! empty($apiToken) && ! empty($serviceId)) {
            \Paynl\Config::setApiToken($apiToken);
            \Paynl\Config::setServiceId($serviceId);

            return true;
        }

        return false;
    }

    public function getApiToken()
    {
        return trim($this->store->getConfig('payment/paynl/apitoken'));
    }

    public function getTokencode()
    {
        return trim($this->store->getConfig('payment/paynl/tokencode'));
    }

    public function getServiceId()
    {
        return trim($this->store->getConfig('payment/paynl/serviceid'));
    }

    public function getIconUrl() {
        $url = 'https://www.pay.nl/images/payment_profiles/50x32/#paymentOptionId#.png';
        $iconUrl = trim($this->store->getConfig('payment/paynl/iconurl'));

        return empty($iconUrl)?$url:$iconUrl;
    }
}