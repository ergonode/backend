<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Category\Application\Provider\CategoryFormProvider;
use Limenius\Liform\Liform;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path="/categories/{type}/configuration",
 *     methods={"GET"},
 * )
 */
class CategoryTypeConfigurationAction
{
    private CategoryFormProvider $formProvider;

    private FormFactoryInterface $formFactory;

    private Liform $liForm;

    public function __construct(CategoryFormProvider $formProvider, FormFactoryInterface $formFactory, Liform $liForm)
    {
        $this->formProvider = $formProvider;
        $this->formFactory = $formFactory;
        $this->liForm = $liForm;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_CATEGORY_GET_TYPE_CONFIGURATION")
     *
     * @SWG\Tag(name="Category")
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
     *     description="Returns category form configuration",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     */
    public function __invoke(string $type): Response
    {
        $class = $this->formProvider->provide($type);
        $form = $this->formFactory->create($class);

        $result = json_encode($this->liForm->transform($form), JSON_THROW_ON_ERROR, 512);

        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }
}
