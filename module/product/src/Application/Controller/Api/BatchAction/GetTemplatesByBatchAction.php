<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\BatchAction;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\BatchAction\Application\Controller\Api\Factory\BatchActionFilterFactory;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterDisabled;
use Ergonode\Product\Application\Form\Product\BatchAction\BatchActionTemplatesForm;
use Ergonode\Product\Application\Form\Product\BatchAction\Model\BatchActionTemplateFormModel;
use Ergonode\Product\Infrastructure\Filter\BatchAction\TemplateBatchActionFilter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/batch-action/templates", methods={"POST"})
 */
class GetTemplatesByBatchAction
{
    private FormFactoryInterface $formFactory;
    private BatchActionFilterFactory $factory;
    private TemplateBatchActionFilter $templateBatchActionFilter;

    public function __construct(
        FormFactoryInterface $formFactory,
        BatchActionFilterFactory $factory,
        TemplateBatchActionFilter $templateBatchActionFilter
    ) {
        $this->formFactory = $formFactory;
        $this->factory = $factory;
        $this->templateBatchActionFilter = $templateBatchActionFilter;
    }

    /**
     * @SWG\Tag(name="Batch action")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add filter",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/batch-action-filter")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns template IDs",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        try {
            $form = $this->formFactory->create(BatchActionTemplatesForm::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var BatchActionTemplateFormModel $data */
                $data = $form->getData();
                $filter = 'all' === $data->filter ?
                    new BatchActionFilterDisabled() :
                    $this->factory->create($data->filter);

                $filteredIds = $this->templateBatchActionFilter->filter($filter);

                return new SuccessResponse($filteredIds);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
