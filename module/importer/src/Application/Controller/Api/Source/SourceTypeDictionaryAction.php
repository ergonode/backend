<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Importer\Infrastructure\Provider\SourceTypeDictionaryProvider;

/**
 * @Route(
 *     path="/dictionary/sources",
 *     methods={"GET"},
 * )
 */
class SourceTypeDictionaryAction
{
    private SourceTypeDictionaryProvider $provider;

    public function __construct(SourceTypeDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import source dictionary",
     * )
     */
    public function __invoke(Language $language): array
    {
        return $this->provider->provide($language);
    }
}
