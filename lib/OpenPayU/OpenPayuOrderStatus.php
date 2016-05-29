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
    const STATUS_NEW = 'NEW';
    const STATUS_PENDING = 'PENDING';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_WAITING_FOR_CONFIRMATION = 'WAITING_FOR_CONFIRMATION';

}
