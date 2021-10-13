<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Multimedia\Domain\Query\MultimediaTypeQueryInterface;

class ImportProductImageAttributeStrategy implements ImportProductAttributeStrategyInterface
{
    private MultimediaQueryInterface $multimediaQuery;

    private MultimediaTypeQueryInterface $typeQuery;

    public function __construct(MultimediaQueryInterface $multimediaQuery, MultimediaTypeQueryInterface $typeQuery)
    {
        $this->multimediaQuery = $multimediaQuery;
        $this->typeQuery = $typeQuery;
    }

    public function supported(AttributeType $type): bool
    {
        return ImageAttribute::TYPE === $type->getValue();
    }

    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface
    {
        $result = [];
        foreach ($value->getTranslations() as $language => $version) {
            if ($version) {
                $multimediaId = $this->multimediaQuery->findIdByFilename($version);
                if (null === $multimediaId) {
                    throw new ImportException('Missing {version} multimedia.', ['{version}' => $version]);
                }

                if ('image' !== $this->typeQuery->findMultimediaType($multimediaId)) {
                    throw new ImportException('Only image file can be set as image attribute value');
                }

                $result[$language] = $multimediaId->getValue();
            }
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
