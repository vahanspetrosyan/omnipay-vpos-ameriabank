<?php

namespace Omnipay\Ameriabank\Message;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Class PurchaseRequest
 * @package Omnipay\Ameriabank\Message
 */
class PurchaseRequest extends AbstractRequest
{

    /**
     * Gateway payment Url
     * @var string
     */
    protected $paymentUrl = 'https://payments.ameriabank.am/webservice/PaymentService.svc?wsdl';
    
    protected $paymentTestUrl = 'https://testpayments.ameriabank.am/webservice/PaymentService.svc?wsdl';

    /**
     * Gateway Card Bindings Url
     * @var string
     */
    protected $cardBindingsUrl = 'https://payments.ameriabank.am/webservice/CardBindings.svc?wsdl';

    protected $cardBindingsTestUrl = 'https://testpayments.ameriabank.am/webservice/CardBindings.svc?wsdl';


    /**
     * Currency ISO codes.
     *
     * @var array
     */
    protected static $currencyISOCodes = [
        'AMD' => '051',
        'USD' => '840',
        'EUR' => '978',
        'RUB' => '643'
    ];


    /**
     * Sets the request language.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('lang', $value);
    }


    /**
     * Get the request language.
     * @return $this
     */
    public function getLanguage()
    {
        return $this->getParameter('lang');
    }


    /**
     * Sets the request client ID.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }


    /**
     * Get the request client ID.
     * @return $this
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }


    /**
     * Sets the request username.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }


    /**
     * Get the request username.
     * @return $this
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }


    /**
     * Sets the request Opaque.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setOpaque($value)
    {
        return $this->setParameter('opaque', $value);
    }


    /**
     * Get the request Opaque.
     * @return $this
     */
    public function getOpaque()
    {
        return $this->getParameter('opaque');
    }


    /**
     * Sets the request TransactionId.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setTransactionId($value)
    {
        return $this->setParameter('orderID', $value);
    }


    /**
     * Get the request TransactionId.
     * @return $this
     */
    public function getTransactionId()
    {
        return $this->getParameter('orderID');
    }



    /**
     * Sets the request password.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }


    /**
     * Get the request password.
     * @return $this
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }


    /**
     * Sets the request payment id.
     *
     * @param $value
     *
     * @return $this
     */
    public function setPaymentId($value)
    {
        return $this->setParameter('paymentid', $value);
    }


    /**
     * Get the request payment id.
     * @return $this
     */
    public function getPaymentId()
    {
        return $this->getParameter('paymentid');
    }


    /**
     * get payment Url
     * @return string
     */
    public function getPaymentUrl()
    {
        return $this->getTestMode() ? $this->paymentTestUrl : $this->paymentUrl;
    }


    /**
     * get Card Bindings Url
     * @return mixed
     */
    public function getCardBindingsUrl()
    {
        return $this->getTestMode() ? $this->cardBindingsTestUrl : $this->cardBindingsUrl;
    }


    /**
     * Sets the request ClientUrl.
     *
     * @param $value
     *
     * @return $this
     */
    public function setClientUrl($value)
    {
        return $this->setParameter('clienturl', $value);
    }


    /**
     * Get the request Client Url.
     * @return $this
     */
    public function getClientUrl()
    {
        return $this->getParameter('clienturl');
    }


    /**
     * Sets the request CardHolderId.
     *
     * @param $value
     *
     * @return $this
     */
    public function setCardHolderId($value)
    {
        return $this->setParameter('CardHolderID', $value);
    }


    /**
     * Get the request CardHolderId.
     * @return $this
     */
    public function getCardHolderId()
    {
        return $this->getParameter('CardHolderID');
    }


    /**
     * Sets the request binding purchase.
     *
     * @param $value
     *
     * @return $this
     */
    public function setBindingPurchase($value)
    {
        return $this->setParameter('bindingPurchase', $value);
    }


    /**
     * Get the request binding purchase.
     * @return $this
     */
    public function getBindingPurchase()
    {
        return $this->getParameter('bindingPurchase');
    }


    /**
     * Prepare data to send
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('clientId', 'username', 'password');
        return [
            'Opaque'            => $this->getOpaque(),
            'backURL'           => $this->getReturnUrl(),
            'OrderID'           => $this->getTransactionId(),
            'Username'          => $this->getUsername(),
            'Password'          => $this->getPassword(),
            'ClientID'          => $this->getClientId(),
            'Description'       => $this->getDescription(),
            'Currency'          => self::$currencyISOCodes[$this->getCurrency()],
            'PaymentAmount'     => $this->getAmount(),
            'clienturl'         => $this->getClientUrl(),
            'paymentid'         => $this->getPaymentId(),
            'lang'              => $this->getLanguage(),
            'testMode'          => $this->getTestMode(),
            'CardHolderID'      => $this->getCardHolderId(),
            'bindingPurchase'   => $this->getBindingPurchase(),
        ];
    }


    /**
     * Send data and return response instance
     * @param $data
     * @return PurchaseResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {

        if (!empty($data['bindingPurchase']) && !empty($data['CardHolderID'])) {

            $payment = $this->createBindingRequest($data);

            if ($payment->DoBindingTransactionResult->respcode == '00') {
                $data['PaymentId'] = $payment->DoBindingTransactionResult->rrn;
            }

            $data['message'] = $payment->DoBindingTransactionResult->descr;

            return $this->response = new PurchaseResponse($this, $data);
        } else {
            $payment = $this->createPaymentRequest($data);

            if (!empty($payment->GetPaymentIDResult->PaymentID) && $payment->GetPaymentIDResult->Respmessage == 'OK') {
                $data['PaymentId'] = $payment->GetPaymentIDResult->PaymentID;
            }

            $data['message'] = $payment->GetPaymentIDResult->Respmessage;

            return $this->response = new PurchaseResponse($this, $data);
        }
    }


    /**
     * Create Payment Request Ameria Bank
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function createPaymentRequest($data)
    {
        $args['paymentfields'] = $data;

        $client = new \SoapClient($this->getPaymentUrl(), [
            'soap_version'    => SOAP_1_1,
            'exceptions'      => true,
            'trace'           => 1,
            'wsdl_local_copy' => true
        ]);

        return $webService = $client->GetPaymentID($args);
    }


    /**
     * Create Do Binding Transaction Ameria Bank
     * @param $data
     * @return mixed
     */
    protected function createBindingRequest($data)
    {
        $args['payclient'] = $data;
        $client = new \SoapClient($this->getCardBindingsUrl(), [
            'soap_version'    => SOAP_1_1,
            'exceptions'      => true,
            'trace'           => 1,
            'wsdl_local_copy' => true
        ]);

        return $webService = $client->DoBindingTransaction($args);
    }


}