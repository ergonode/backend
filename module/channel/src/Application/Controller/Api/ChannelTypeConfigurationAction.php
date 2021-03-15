<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Limenius\Liform\Liform;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryProvider;
use Ergonode\Channel\Application\Provider\ChannelTypeProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route(
 *     path="/channels/{type}/configuration",
 *     methods={"GET"},
 * )
 */
class ChannelTypeConfigurationAction
{
    private ChannelFormFactoryProvider $factoryProvider;

    private ChannelTypeProvider $typeProvider;

    private Liform $liForm;

    public function __construct(
        ChannelFormFactoryProvider $factoryProvider,
        ChannelTypeProvider $typeProvider,
        Liform $liForm
    ) {
        $this->factoryProvider = $factoryProvider;
        $this->typeProvider = $typeProvider;
        $this->liForm = $liForm;
    }

    /**
     * @IsGranted("CHANNEL_GET_CONFIGURATION_GRID")
     *
     * @SWG\Tag(name="Channel")
     *
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
     *     description="Returns JSON Schema configuration for given type channel form",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \JsonException
     */
    public function __invoke(string $type): Response
    {
        $types = $this->typeProvider->provide();

        if (!in_array($type, $types)) {
            throw new NotFoundHttpException(sprintf('Can\'t find configuration for type "%s"', $type));
        }

        $form = $this->factoryProvider->provide($type)->create();

        $result = json_encode($this->liForm->transform($form), JSON_THROW_ON_ERROR, 512);

        return new SuccessResponse($result);
    }
}
