<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Infrastructure\Provider\ExportProfileTypeDictionaryProvider;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path="/dictionary/export-profile",
 *     methods={"GET"},
 * )
 */
class ExportProfileTypeDictionaryAction
{
    /**
     * @var ExportProfileTypeDictionaryProvider
     */
    private ExportProfileTypeDictionaryProvider $provider;


    /**
     * @param ExportProfileTypeDictionaryProvider $provider
     */
    public function __construct(ExportProfileTypeDictionaryProvider $provider)
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
     *     description="Returns export profile dictionary",
     * )
     * @param Language $language
     *
     * @return Response
     */
    public function __invoke(Language $language)
    {
        $dictionary = $this->provider->provide($language);

        return new SuccessResponse($dictionary);
    }
}
