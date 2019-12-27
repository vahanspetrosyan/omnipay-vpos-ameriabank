<?php
namespace Omnipay\Ameriabank\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Refund Request
 *
 * @method Response send()
 */
class RefundRequest extends AbstractRequest
{

    /**
     * Gateway payment Url
     * @var string
     */
    protected $refundUrl = 'https://payments.ameriabank.am/webservice/PaymentService.svc?wsdl';

    protected $refundTestUrl = 'https://testpayments.ameriabank.am/webservice/PaymentService.svc?wsdl';

    /**
     * Get payment Url
     * @return string
     */
    public function getRefundUrl()
    {
        return $this->getTestMode() ? $this->refundTestUrl : $this->refundUrl;
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
     * @return array|mixed
     */
    public function getData()
    {
        $this->validate('clientId', 'username', 'password');
        return [
            'OrderID'           => $this->getTransactionId(),
            'Username'          => $this->getUsername(),
            'Password'          => $this->getPassword(),
            'ClientID'          => $this->getClientId(),
            'Description'       => $this->getDescription(),
            'PaymentAmount'     => $this->getAmount()
        ];
    }

    /**
     * @param $data
     *
     * @return \Omnipay\Ameriabank\Message\RefundResponse
     */
    public function sendData($data)
    {
        $args['paymentfields'] = $data;
        
        $client = new \SoapClient($this->getRefundUrl(), [
            'soap_version'    => SOAP_1_1,
            'exceptions'      => true,
            'trace'           => 1,
            'wsdl_local_copy' => true
        ]);

        $response = $client->ReversePayment($args);

        return new RefundResponse($this, $response);
    }
}
