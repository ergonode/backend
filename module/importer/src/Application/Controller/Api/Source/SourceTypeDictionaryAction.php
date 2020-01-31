<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
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
    /**
     * @var SourceTypeDictionaryProvider
     */
    private SourceTypeDictionaryProvider $provider;

    /**
     * @param SourceTypeDictionaryProvider $provider
     */
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
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import source dictionary",
     * )
     * @param Language $language
     *
     * @return Response
     */
    public function __invoke(Language $language): Response
    {
        $dictionary = $this->provider->provide($language);

        return new SuccessResponse($dictionary);
    }
}
