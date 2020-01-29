<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Importer\Infrastructure\Provider\SourceServiceProvider;

/**
 * @Route(
 *     name="ergonode_sourcet_configuration",
 *     path="/sources/{source}/configuration",
 *     methods={"GET"},
 *     requirements={"source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SourceGetConfigurationAction
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
     *     name="source",
     *     in="path",
     *     type="string",
     *     description="Source id",
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
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Source\AbstractSource")
     *
     * @param AbstractSource $source
     *
     * @return Response
     *
     */
    public function __invoke(AbstractSource $source): Response
    {
        $service = $this->provider->provide($source->getType());

        $configuration = $service->process($source);

        return new SuccessResponse($configuration);
    }
}
