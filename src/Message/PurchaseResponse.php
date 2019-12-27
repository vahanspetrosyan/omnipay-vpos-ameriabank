<?php
namespace Omnipay\Ameriabank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
/**
 * Class PurchaseResponse
 * @package Omnipay\Ameriabank\Message
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * Gateway payment Url
     * @var string
     */
    protected $paymentUrl = 'https://payments.ameriabank.am/webservice/PaymentService.svc?wsdl';
    protected $paymentTestUrl = 'https://testpayments.ameriabank.am/webservice/PaymentService.svc?wsdl';


    /**
     * Gateway $endpoint
     * @var string
     */
    protected $endpoint = 'https://payments.ameriabank.am/forms/frm_paymentstype.aspx';
    protected $endpointTest = 'https://testpayments.ameriabank.am/forms/frm_paymentstype.aspx';


    /**
     * Set successful to false, as transaction is not completed yet
     * @return bool
     */
    public function isSuccessful()
    {
        if (!empty($this->data['PaymentId']) && !empty($this->data['bindingPurchase'])) {
            return true;
        }
        return false;
    }


    /**
     * Mark purchase as redirect type
     * @return bool
     */
    public function isRedirect()
    {
        if (!empty($this->data['PaymentId']) && empty($this->data['bindingPurchase'])) {
            return true;
        }
        return false;
    }

    /**
     * Get Message
     * @return null|string
     */
    public function getMessage()
    {
        return $this->data['message'];
    }


    /**
     * Get redirect URL
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->data['testMode'] ? $this->endpointTest.'?'.http_build_query($this->getRedirectData())
                                       : $this->endpoint.'?'.http_build_query($this->getRedirectData());
    }


    /**
     * Get redirect method
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }


    /**
     * Get redirect data
     * @return array|mixed
     */
    public function getRedirectData()
    {
        return $this->data;
    }
}
