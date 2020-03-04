<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\TranslationDeepl\Infrastructure\Provider\UsageDeeplProviderInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/translation/usage", methods={"GET"})
 */
class UsageReadAction
{
    /**
     * @var UsageDeeplProviderInterface
     */
    private UsageDeeplProviderInterface $usageProvider;

    /**
     * @param UsageDeeplProviderInterface $usageProvider
     */
    public function __construct(UsageDeeplProviderInterface $usageProvider)
    {
        $this->usageProvider = $usageProvider;
    }

    /**
     * @SWG\Tag(name="Translation Deepl")
     * @SWG\Response(
     *     response=200,
     *     description="Returns usage information",
     * )
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        $usage = $this->usageProvider->provide();

        return new SuccessResponse([
            'current' => $usage->getCharacterCount(),
            'limit' => $usage->getCharacterLimit(),
        ]);
    }
}
