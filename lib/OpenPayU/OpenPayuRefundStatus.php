<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

/**
 * Class OpenPayuOrderStatus
 */
abstract class OpenPayuRefundStatus
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_FINALIZED = 'FINALIZED';
}
