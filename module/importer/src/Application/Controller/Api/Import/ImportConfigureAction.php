<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Import;

use Ergonode\Api\Application\Response\CreatedResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Importer\Infrastructure\Provider\SourceServiceProvider;

/**
 * @Route(
 *     name="ergonode_import_configure",
 *     path="/imports/{import}/configuration",
 *     methods={"GET"},
 *     requirements={"import" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ImportConfigureAction
{
    /**
     * @var SourceServiceProvider
     */
    private SourceServiceProvider $provider;

    /**
     * @param SourceServiceProvider $provider
     */
    public function __construct(SourceServiceProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @IsGranted("IMPORT_CREATE")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="import",
     *     in="path",
     *     type="string",
     *     description="Import id",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns import ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\AbstractImport")
     *
     * @param AbstractImport $import
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractImport $import): Response
    {
        $service = $this->provider->provide($import->getSourceType());

        $configuration = $service->process($import);

        return new CreatedResponse($configuration);
    }
}
