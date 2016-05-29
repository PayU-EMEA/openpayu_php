<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

/**
 * Class OpenPayuOrderStatus
 */
abstract class OpenPayuOrderStatus
{
    const NEW = 'NEW';
    const PENDING = 'PENDING';
    const CANCELED = 'CANCELED';
    const REJECTED = 'REJECTED';
    const COMPLETED = 'COMPLETED';
    const WAITING_FOR_CONFIRMATION = 'WAITING_FOR_CONFIRMATION';

}
