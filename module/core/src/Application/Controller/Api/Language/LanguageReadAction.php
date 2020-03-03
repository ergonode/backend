<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller\Api\Language;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_core_language_read",
 *     path="/languages/{translationLanguage}",
 *     methods={"GET"},
 *     requirements={"translationLanguage"="[A-Z]{2}"}
 * )
 */
class LanguageReadAction
{
    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $query;

    /**
     * @param LanguageQueryInterface $query
     */
    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Language")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="translationLanguage",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="translation language code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns language",
     *     @SWG\Schema(ref="#/definitions/language_res")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param string $translationLanguage
     *
     * @return Response
     */
    public function __invoke(string $translationLanguage): Response
    {
        $language = $this->query->getLanguage($translationLanguage);

        if ($language) {
            return new SuccessResponse([$language]);
        }

        throw new NotFoundHttpException();
    }
}
