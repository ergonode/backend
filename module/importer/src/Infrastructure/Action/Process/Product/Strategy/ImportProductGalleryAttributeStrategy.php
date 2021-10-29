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
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Multimedia\Domain\Query\MultimediaTypeQueryInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;

class ImportProductGalleryAttributeStrategy implements ImportProductAttributeStrategyInterface
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
        return GalleryAttribute::TYPE === $type->getValue();
    }

    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface
    {
        $result = [];
        foreach ($value->getTranslations() as $language => $version) {
            $collection = [];
            foreach (explode(',', $version) as $item) {
                if (!$item) {
                    continue;
                }

                $item = trim($item);

                $multimediaId = $this->multimediaQuery->findIdByFilename($item);
                if (null === $multimediaId) {
                    throw new ImportException('Missing "{item}" multimedia.', ['{item}' => $item]);
                }

                if ('image' !== $this->typeQuery->findMultimediaType($multimediaId)) {
                    throw new ImportException('Only images file can be set as gallery attribute value');
                }
                $collection[] = $multimediaId->getValue();
            }

            if (!$collection) {
                continue;
            }

            $result[$language] = implode(',', $collection);
        }

        return new StringCollectionValue($result);
    }
}
