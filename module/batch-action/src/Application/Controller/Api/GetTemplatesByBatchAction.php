<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\BatchAction\Application\Form\Model\BatchActionFilterFormModel;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterDisabled;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use Ergonode\BatchAction\Infrastructure\Filter\TemplateBatchActionFilter;
use Ergonode\SharedKernel\Application\Serializer\Exception\DenoralizationException;
use Ergonode\SharedKernel\Application\Serializer\NormalizerInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/batch-action/templates", methods={"GET"})
 */
class GetTemplatesByBatchAction
{
    private TemplateBatchActionFilter $templateBatchActionFilter;

    private NormalizerInterface $normalizer;

    private ValidatorInterface $validator;


    public function __construct(
        TemplateBatchActionFilter $templateBatchActionFilter,
        NormalizerInterface $normalizer,
        ValidatorInterface $validator
    ) {
        $this->templateBatchActionFilter = $templateBatchActionFilter;
        $this->normalizer = $normalizer;
        $this->validator = $validator;
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
        $filter = $request->query->get('filter');
        if (null === $filter) {
            throw new BadRequestHttpException('Filter has to be object or `all` value');
        }
        try {
            /** @var BatchActionFilterFormModel $data */
            $data = 'all' === $filter ?
                new BatchActionFilterDisabled() :
                $this->normalizer->denormalize(
                    $request->query->get('filter') ?? [],
                    BatchActionFilterFormModel::class
                );
            $violations = $this->validator->validate($data);
            if (0 === $violations->count()) {
                $ids = null;
                if ($data->ids ?? null) {
                    $list = [];
                    foreach ($data->ids->list as $id) {
                        $list[] = new AggregateId($id);
                    }
                    $ids = new BatchActionIds($list, $data->ids->included);
                }
                $filter = new BatchActionFilter($ids, $data->query ?? null);
                $filteredIds = $this->templateBatchActionFilter->filter($filter);

                return new SuccessResponse($filteredIds);
            }
            throw new ViolationsHttpException($violations);
        } catch (DenoralizationException $exception) {
            throw new BadRequestHttpException('Invalid query filter');
        }
    }
}
