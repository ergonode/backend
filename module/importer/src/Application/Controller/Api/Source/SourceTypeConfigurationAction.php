<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Importer\Application\Provider\SourceFormFactoryProvider;
use Limenius\Liform\Liform;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_source_configuration",
 *     path="/sources/{type}/configuration",
 *     methods={"GET"}
 * )
 */
class SourceTypeConfigurationAction
{
    /**
     * @var SourceFormFactoryProvider
     */
    private SourceFormFactoryProvider $provider;

    /**
     * @var Liform
     */
    private Liform $liform;

    /**
     * @param SourceFormFactoryProvider $provider
     * @param Liform                    $liform
     */
    public function __construct(SourceFormFactoryProvider $provider, Liform $liform)
    {
        $this->provider = $provider;
        $this->liform = $liform;
    }

    /**
     * @IsGranted("IMPORT_READ")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
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
     * @param string $type
     *
     * @return Response
     */
    public function __invoke(string $type): Response
    {
            $form = $this->provider->provide($type)->create();

            $result = json_encode($this->liform->transform($form), JSON_THROW_ON_ERROR, 512);

            return new SuccessResponse($result);
    }
}
