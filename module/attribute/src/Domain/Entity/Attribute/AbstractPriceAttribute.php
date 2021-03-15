<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Money\Currency;

abstract class AbstractPriceAttribute extends AbstractAttribute
{
    public const TYPE = 'PRICE';
    public const CURRENCY = 'currency';

    /**
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

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getCurrency(): Currency
    {
        return new Currency($this->getParameter(self::CURRENCY));
    }

    /**
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
                        $new->getCode()
                    )
                );
        }
    }
}
