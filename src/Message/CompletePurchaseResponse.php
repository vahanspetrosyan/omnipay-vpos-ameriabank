<?php
namespace Omnipay\Ameriabank\Message;
use Omnipay\Common\Message\AbstractResponse;
/**
 * Class CompletePurchaseResponse
 * @package Omnipay\Ameriabank\Message
 */
class CompletePurchaseResponse extends AbstractResponse
{

    /**
     * Indicates whether transaction was successful
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->data->GetPaymentFieldsResult->respcode == '00';
    }

    /**
     * Get TransactionId
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data->GetPaymentFieldsResult->orderid ?? '';
    }


    /**
     * Get
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->getTransactionId();
    }


    /**
     * Get Message
     * @return null|string
     */
    public function getMessage()
    {
        return $this->data->GetPaymentFieldsResult->descr;
    }

}