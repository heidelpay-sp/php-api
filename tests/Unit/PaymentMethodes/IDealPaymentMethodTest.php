<?php
namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethodes;
use PHPUnit\Framework\TestCase;
use \Heidelpay\PhpApi\PaymentMethodes\IDealPaymentMethod as  iDeal;
/**
 *
 *  iDeal Test
 *
 *  Connection tests can fail due to network issues and scheduled downtimes.
 *  This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category UnitTest
 */

class IdealPaymentMerhodTest extends TestCase
{
    /** 
     * SecuritySender
     * @var string SecuritySender
     */
    protected $SecuritySender = '31HA07BC8142C5A171745D00AD63D182'; 
    /**
     * UserLogin
     * @var string UserLogin
     */
    protected $UserLogin      = '31ha07bc8142c5a171744e5aef11ffd3'; 
    /**
     * UserPassword
     * @var string UserPassword
     */
    protected $UserPassword   = '93167DE7';
    /**
     * TransactionChannel
     * 
     * @var string TransactionChannel
     */
    protected $TransactionChannel = '31HA07BC8142C5A171744B56E61281E5';
    /**
     * SandboxRequest
     * 
     * Request will be send to Heidelpay sandbox payment system.
     * 
     * @var string
     */
    protected $SandboxRequest = TRUE ;
    
    /**
     * Customer given name
     * @var string nameGiven
     */
    protected $nameGiven = 'Heidel';
    /**
     * Customer family name
     * @var string nameFamily
     */
    protected $nameFamily ='Berger-Payment';
    /**
     * Customer company name
     * @var string nameCompany
     */
    protected $nameCompany = 'DevHeidelpay';
    /**
     * Customer id
     * @var string shopperId
     */
    protected $shopperId = '12344';
    /**
     * customer billing address street
     * @var string addressStreet
     */
    protected $addressStreet = 'Vagerowstr. 18';
    /**
     * customer billing address state
     * @var string addressState
     */
    protected $addressState  = 'DE-BW';
    /**
     * customer billing address zip
     * @var string addressZip
     */
    protected $addressZip    = '69115';
    /**
     * customer billing address city
     * @var string addressCity
     */
    protected $addressCity    = 'Heidelberg';
    /**
     * customer billing address city
     * @var string addressCity
     */
    protected $addressCountry = 'DE';
    /**
     * customer mail address
     * @var string contactMail
     */
    protected $contactMail = "development@heidelpay.de";
    
    /**
     * Transaction currency
     * @var string currency
     */
    
    protected   $currency = 'EUR';
    /**
     * Secret
     * 
     * The secret will be used to generate a hash using 
     * transaction id + secret. This hash can be used to
     * verify the the payment response. Can be used for
     * brute force protection.
     * @var string secret
     */
    protected   $secret = 'Heidelpay-PhpApi';
    
    /**
     * PaymentObject
     * @var \Heidelpay\PhpApi\PaymentMethodes\SofortPaymentMethod
     */
    protected $paymentObject = NULL;
    /**
     * Constructor used to set timezone to utc
     */
  public function __construct() {
      date_default_timezone_set('UTC');
  }
  /**
   * Set up function will create a sofort object for each testcase
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function  setUp() {
  	$iDeal = new iDeal();
  	
  	$iDeal->getRequest()->authentification($this->SecuritySender, $this->UserLogin, $this->UserPassword, $this->TransactionChannel, 'TRUE');
  	
  	$iDeal->getRequest()->customerAddress($this->nameGiven, $this->nameFamily, NULL, $this->shopperId, $this->addressStreet,$this->addressState,$this->addressZip, $this->addressCity, $this->addressCountry, $this->contactMail);
  	
  	
  	$iDeal->_dryRun=TRUE;
  	
  	$this->paymentObject = $iDeal;
  	
  }
  
  /**
   * Get current called method, without namespace
   * @param string $method
   * @return string class and method
   */
  public function getMethod($method) {
      return substr(strrchr($method, '\\'), 1);
  }
    
  /**
   * Test case for a single iDeal authorize
   * @return string payment reference id for the iDeal authorize transaction
   * @group connectionTest
   */
  public function testAuthorize()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
      $this->paymentObject->getRequest()->async('DE','https://dev.heidelpay.de');
      
  	  $this->paymentObject->authorize();
  	  
  	  /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->prepareRequest();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
      
      /* test if config parameters exists */
      $configBankCountry = array('NL' => 'Niederlande');
      
      $this->assertEquals($configBankCountry, $response[1]->getConfig()->getBankCountry());
      
      $configBrands = array(
            'ING_TEST' => 'Test Bank', 
            'INGBNL2A' => 'Issuer Simulation V3 - ING',
            'RABONL2U' => 'Issuer Simulation V3 - RABO'
      );
        
      $this->assertEquals($configBrands, $response[1]->getConfig()->getBrands());
      
      
      
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1],1));
      $this->assertFalse($response[1]->isError(),'authorize failed : '.print_r($response[1]->getError(),1));
      
      return (string)$response[1]->getPaymentReferenceId();
  }
  
}