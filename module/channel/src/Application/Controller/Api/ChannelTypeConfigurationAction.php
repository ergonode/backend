<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Limenius\Liform\Liform;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryProvider;

/**
 * @Route(
 *     path="/channels/{type}/configuration",
 *     methods={"GET"},
 * )
 */
class ChannelTypeConfigurationAction
{
    /**
     * @var ChannelFormFactoryProvider
     */
    private ChannelFormFactoryProvider $provider;

    /**
     * @var Liform
     */
    private Liform $liForm;

    /**
     * @param ChannelFormFactoryProvider $provider
     * @param Liform                     $liForm
     */
    public function __construct(ChannelFormFactoryProvider $provider, Liform $liForm)
    {
        $this->provider = $provider;
        $this->liForm = $liForm;
    }

    /**
     * @IsGranted("EXPORT_PROFILE_READ")
     *
     * @SWG\Tag(name="Channel")
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
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
     * @param string $type
     *
     * @return Response
     *
     * @throws \JsonException
     */
    public function __invoke(string $type)
    {
        $form = $this->provider->provide($type)->create();

        $result = json_encode($this->liForm->transform($form), JSON_THROW_ON_ERROR, 512);

        return new SuccessResponse($result);
    }
}
