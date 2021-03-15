<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\BatchAction\Application\Controller\Api\Factory\BatchActionFilterFactory;
use Ergonode\BatchAction\Application\Form\BatchActionForm;
use Ergonode\BatchAction\Application\Form\Model\BatchActionFormModel;
use Ergonode\BatchAction\Domain\Count\CountInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterDisabled;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/batch-action/count", methods={"POST"})
 */
class GetResourcesCountAction
{
    private FormFactoryInterface $formFactory;
    private BatchActionFilterFactory $factory;
    private CountInterface $count;

    public function __construct(
        FormFactoryInterface $formFactory,
        BatchActionFilterFactory $factory,
        CountInterface $count
    ) {
        $this->formFactory = $formFactory;
        $this->factory = $factory;
        $this->count = $count;
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
     *     description="Returns count",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="count",
     *              type="integer"
     *          )
     *     )
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
            $form = $this->formFactory->create(BatchActionForm::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var BatchActionFormModel $data */
                $data = $form->getData();
                $type = new BatchActionType($data->type);
                if (!$this->count->supports($type)) {
                    throw new BadRequestHttpException("Unsupported type {$data->type}");
                }
                $filter = 'all' === $data->filter ?
                    new BatchActionFilterDisabled() :
                    $this->factory->create($data->filter);

                $count = $this->count->count($type, $filter);

                return new SuccessResponse(['count' => $count]);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
