<?php
namespace Omnipay\Ameriabank\Message;


use Symfony\Component\HttpFoundation\ParameterBag;
/**
 * Class CompletePurchaseRequest
 * @package Omnipay\Ameriabank\Message
 */
class CompletePurchaseRequest extends PurchaseRequest
{

    /**
     * Gateway payment Url
     * @var string
     */
    protected $paymentUrl = 'https://payments.ameriabank.am/webservice/PaymentService.svc?wsdl';
    protected $paymentTestUrl = 'https://testpayments.ameriabank.am/webservice/PaymentService.svc?wsdl';


    /**
     * get payment Url
     * @return string
     */
    public function getPaymentUrl()
    {
        return $this->getTestMode() ? $this->paymentTestUrl : $this->paymentUrl;
    }


    /**
     * @param $value
     * @return $this
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }


    /**
     * Get Amount
     * @return mixed
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }


    /**
     * Prepare and get data
     * @return mixed|void
     */
    public function getData()
    {
        return $this->validateRequest($this->httpRequest->request);
    }


    /**
     * Send data and return response
     *
     * @param mixed $data
     *
     * @return \Omnipay\Common\Message\ResponseInterface|\Omnipay\Ameriabank\Message\CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $this->getPaymentFieldsResult($data));
    }


    /**
     * Validate request and return data, merchant has to echo with just 'OK' at the end
     *
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestData
     *
     * @return array
     */
    protected function validateRequest(ParameterBag $requestData)
    {
        $data = $requestData->all();

        $data['success'] = false;
        // Check for required request data
        if ($requestData->has('Username') &&
            $requestData->has('ClientID') &&
            $requestData->has('Password') &&
            $requestData->has('OrderID') &&
            $requestData->has('PaymentAmount')) {
            $data['success'] = true;
        }
        return $data;
    }


    /**
     * Get Payment Fields Ameria Bank
     * @return mixed
     */
    protected function getPaymentFieldsResult($data)
    {
        $client = new \SoapClient($this->getPaymentUrl(), [
            'soap_version'    => SOAP_1_1,
            'exceptions'      => true,
            'trace'           => 1,
            'wsdl_local_copy' => true
        ]);

        $args['paymentfields'] = array(
            'OrderID'       => $data['orderID'],
            'Username'      => $this->getUsername(),
            'Password'      => $this->getPassword(),
            'ClientID'      => $this->getClientId(),
            'PaymentAmount' => $this->getAmount(),
        );
        
        return $client->GetPaymentFields($args);
    }
}