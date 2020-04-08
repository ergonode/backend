<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\Provider\LanguagePrivilegeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/languages/privileges", methods={"GET"})
 */
class LanguagePrivilegeReadAction
{
    /**
     * @var LanguagePrivilegeDictionaryProvider
     */
    private LanguagePrivilegeDictionaryProvider $provider;

    /**
     * @param LanguagePrivilegeDictionaryProvider $provider
     */
    public function __construct(LanguagePrivilegeDictionaryProvider $provider)
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
     *     default="en",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns language privilege collection"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @param Language $language
     *
     * @return Response
     */
    public function __invoke(Language $language): Response
    {
        $result = $this->provider->provide($language);

        return new SuccessResponse($result);
    }
}
