<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper;

use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class Shopware6ExporterMultimediaException extends Shopware6ExporterException
{
    private const MESSAGE = 'Multimedia not found, id {multimedia}';

    public function __construct(MultimediaId $multimediaId, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            ['{multimedia}' => $multimediaId->getValue()],
            $previous
        );
    }
}
