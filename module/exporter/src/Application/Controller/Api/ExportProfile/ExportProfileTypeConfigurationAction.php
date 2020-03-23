<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryProvider;
use Limenius\Liform\Liform;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;

/**
 * @Route(
 *     path="/export-profile/{type}/configuration",
 *     methods={"GET"},
 * )
 */
class ExportProfileTypeConfigurationAction
{
    /**
     * @var ExportProfileFormFactoryProvider
     */
    private ExportProfileFormFactoryProvider $provider;

    /**
     * @var Liform
     */
    private Liform $liForm;

    /**
     * @param ExportProfileFormFactoryProvider $provider
     * @param Liform                           $liForm
     */
    public function __construct(ExportProfileFormFactoryProvider $provider, Liform $liForm)
    {
        $this->provider = $provider;
        $this->liForm = $liForm;
    }

    /**
     * @IsGranted("EXPORT_PROFILE_READ")
     *
     * @SWG\Tag(name="Export Profile")
     *
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
     *     description="Returns Config Export Profile",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @param string $type
     *
     * @return Response
     */
    public function __invoke(string $type)
    {
        $form = $this->provider->provide($type)->create();

        $result = json_encode($this->liForm->transform($form), JSON_THROW_ON_ERROR, 512);

        return new SuccessResponse($result);
    }
}
