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
use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsInterface;
use Ergonode\Core\Application\Exception\DenoralizationException;
use Ergonode\Core\Application\Serializer\JMSSerializer;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @Route("/batch-action/templates", methods={"GET"})
 */
class GetTemplatesByBatchAction
{
    private BatchActionFilterIdsInterface $batchActionFilter;

    private TemplateQueryInterface $templateQuery;

    private JMSSerializer $serializer;

    private ValidatorInterface $validator;


    public function __construct(
        BatchActionFilterIdsInterface $batchActionFilter,
        TemplateQueryInterface $templateQuery,
        JMSSerializer $serializer,
        ValidatorInterface $validator
    ) {
        $this->batchActionFilter = $batchActionFilter;
        $this->templateQuery = $templateQuery;
        $this->serializer = $serializer;
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
        try {
            /** @var BatchActionFilterFormModel $data */
            $data = $this->serializer->denormalize(
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

                /** @var ProductId[] $ids */
                $ids = $this->batchActionFilter->filter($filter);
                Assert::allIsInstanceOf($ids, AggregateId::class);
                $result = $this->templateQuery->findTemplateIdsByProductIds($ids);

                return new SuccessResponse($result);
            }
            throw new ViolationsHttpException($violations);
        } catch (DenoralizationException $exception) {
            throw new BadRequestHttpException('Invalid query parameters');
        }
    }
}
