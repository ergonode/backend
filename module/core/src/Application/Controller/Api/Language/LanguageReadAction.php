<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Controller\Api\Language;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_core_language_read",
 *     path="/languages/{translationLanguage}",
 *     methods={"GET"}
 * )
 */
class LanguageReadAction
{
    private LanguageQueryInterface $query;

    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("CORE_GET_LANGUAGE")
     *
     * @SWG\Tag(name="Language")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
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
     *     @SWG\Schema(ref="#/definitions/language_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(string $translationLanguage): Response
    {
        $language = $this->query->getLanguage($translationLanguage);

        if ($language) {
            return new SuccessResponse($language);
        }

        throw new NotFoundHttpException();
    }
}
