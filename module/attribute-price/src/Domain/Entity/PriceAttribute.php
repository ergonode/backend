<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributePrice\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;
use Money\Currency;

/**
 */
class PriceAttribute extends AbstractAttribute
{
    public const TYPE = 'PRICE';
    public const CURRENCY = 'currency';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param Currency           $format
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        Currency $format
    ) {
        parent::__construct($id, $code, $label, $hint, $placeholder, false, [self::CURRENCY => $format->getCode()]);
    }

    /**
     * @JMS\VirtualProperty();
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return new Currency($this->getParameter(self::CURRENCY));
    }

    /**
     * @param Currency $new
     *
     * @throws \Exception
     */
    public function changeCurrency(Currency $new): void
    {
        if ($this->getCurrency()->getCode() !== $new->getCode()) {
            $this->apply(new AttributeParameterChangeEvent(self::CURRENCY, $this->getCurrency()->getCode(), $new->getCode()));
        }
    }
}
