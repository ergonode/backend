<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Resolver;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;

class ImportProductImageAttributeStrategy implements ImportProductAttributeStrategyInterface
{
    private MultimediaQueryInterface $multimediaQuery;

    public function __construct(MultimediaQueryInterface $multimediaQuery)
    {
        $this->multimediaQuery = $multimediaQuery;
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
                Assert::notNull($multimediaId, sprintf('Can\'t find multimedia %s file', $version));
                $result[$language] = $multimediaId->getValue();
            }
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
