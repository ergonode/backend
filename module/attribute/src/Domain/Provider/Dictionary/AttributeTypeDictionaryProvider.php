<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider\Dictionary;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class AttributeTypeDictionaryProvider
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $result[TextAttribute::TYPE] =
            $this->translator->trans(TextAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[TextareaAttribute::TYPE] =
            $this->translator->trans(TextareaAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[NumericAttribute::TYPE] =
            $this->translator->trans(NumericAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[SelectAttribute::TYPE] =
            $this->translator->trans(SelectAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[MultiSelectAttribute::TYPE] =
            $this->translator->trans(MultiSelectAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[PriceAttribute::TYPE] =
            $this->translator->trans(PriceAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[DateAttribute::TYPE] =
            $this->translator->trans(DateAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[UnitAttribute::TYPE] =
            $this->translator->trans(UnitAttribute::TYPE, [], 'attribute', $language->getCode());
        $result[ImageAttribute::TYPE] =
            $this->translator->trans(ImageAttribute::TYPE, [], 'attribute', $language->getCode());

        return $result;
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return [
            TextAttribute::TYPE,
            TextareaAttribute::TYPE,
            NumericAttribute::TYPE,
            SelectAttribute::TYPE,
            MultiSelectAttribute::TYPE,
            PriceAttribute::TYPE,
            DateAttribute::TYPE,
            UnitAttribute::TYPE,
            ImageAttribute::TYPE,
        ];
    }
}
