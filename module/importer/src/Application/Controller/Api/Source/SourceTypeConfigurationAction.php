<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Importer\Application\Provider\SourceFormFactoryProvider;
use Limenius\Liform\Liform;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Importer\Infrastructure\Provider\SourceTypeProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route(
 *     name="ergonode_source_configuration",
 *     path="/sources/{type}/configuration",
 *     methods={"GET"}
 * )
 */
class SourceTypeConfigurationAction
{
    private SourceTypeProvider $typeProvider;

    private SourceFormFactoryProvider $factoryProvider;

    private Liform $liform;

    public function __construct(
        SourceTypeProvider $typeProvider,
        SourceFormFactoryProvider $factoryProvider,
        Liform $liform
    ) {
        $this->typeProvider = $typeProvider;
        $this->factoryProvider = $factoryProvider;
        $this->liform = $liform;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_IMPORT_GET_SOURCE_CONFIGURATION_GRID")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns import ID",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Configuration not found",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     */
    public function __invoke(string $type): Response
    {
        $types = $this->typeProvider->provide();

        if (!in_array($type, $types, true)) {
            throw new NotFoundHttpException(sprintf('Can\'t find configuration for type "%s"', $type));
        }

        $form = $this->factoryProvider->provide($type)->create();
        $result = json_encode($this->liform->transform($form), JSON_THROW_ON_ERROR, 512);

        return new Response($result);
    }
}
