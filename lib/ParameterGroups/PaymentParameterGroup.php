<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
/**
 * This class provides the api parameter for payment code
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */

class PaymentParameterGroup extends AbstractParameterGroup {
    
    /**
     * PaymentCode
     * 
     * This prarameter will be used to set the payment method and type. 
     * Notation is for example OT.PA. The first 2 digits are the payment  
     * method, in this case online transfer  and the last are the type
     * here preautorisation. Normally the PhpApi will set the right payment
     * code, but if you want to learn more about this, have a look in Heidelpay
     * whitelable documentation.
     * 
     * @var string code (mandatory) 
     */
    public $code = NULL;
    
    /**
     * PamyentCode getter
     * @return string code
     */
    
    public function getCode(){
        return $this->code;
    }
}