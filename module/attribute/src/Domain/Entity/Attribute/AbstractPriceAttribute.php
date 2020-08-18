<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Money\Currency;

/**
 */
abstract class AbstractPriceAttribute extends AbstractAttribute
{
    public const TYPE = 'PRICE';
    public const CURRENCY = 'currency';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
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
        AttributeScope $scope,
        Currency $format
    ) {
        parent::__construct(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            [self::CURRENCY => $format->getCode()]
        );
    }

    /**
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
            $this
                ->apply(
                    new AttributeStringParameterChangeEvent(
                        $this->id,
                        self::CURRENCY,
                        $this->getCurrency()->getCode(),
                        $new->getCode()
                    )
                );
        }
    }
}
