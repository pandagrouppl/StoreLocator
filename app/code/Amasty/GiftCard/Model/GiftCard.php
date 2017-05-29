<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model;

class GiftCard extends \Magento\Framework\Model\AbstractModel
{
    const TYPE_VIRTUAL = 1;
    const TYPE_PRINTED = 2;
    const TYPE_COMBINED = 3;

    const PRICE_TYPE_EQUAL = 0;
    const PRICE_TYPE_PERCENT = 1;
    const PRICE_TYPE_FIXED = 2;

    const XML_PATH_LIFETIME 		= 'amgiftcard/card/lifetime';
    const XML_PATH_ALLOW_MESSAGE 	= 'amgiftcard/card/allow_message';
    const XML_PATH_EMAIL_TEMPLATE 	= 'amgiftcard/email/email_template';
}