<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Provider\LanguageTreeProviderInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/languages/tree", methods={"GET"})
 */
class LanguagesTreeReadAction
{
    private LanguageTreeProviderInterface $provider;

    public function __construct(LanguageTreeProviderInterface $provider)
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
     *     description="Returns collection of languages",
     * )
     */
    public function __invoke(Language $language): Response
    {
        $list = $this->provider->getActiveLanguages($language);

        return new SuccessResponse($list);
    }
}
