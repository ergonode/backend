<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\BatchAction\Application\Controller\Api\Factory\BatchActionFilterFactory;
use Ergonode\BatchAction\Application\Form\Model\BatchActionFilterFormModel;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterDisabled;
use Ergonode\BatchAction\Infrastructure\Filter\CountFilter;
use Ergonode\SharedKernel\Application\Serializer\Exception\DenoralizationException;
use Ergonode\SharedKernel\Application\Serializer\NormalizerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/batch-action/count", methods={"GET"})
 */
class GetResourcesCountAction
{
    private BatchActionFilterFactory $factory;
    private CountFilter $countFilter;
    private NormalizerInterface $normalizer;
    private ValidatorInterface $validator;

    public function __construct(
        BatchActionFilterFactory $factory,
        CountFilter $countFilter,
        NormalizerInterface $normalizer,
        ValidatorInterface $validator
    ) {
        $this->factory = $factory;
        $this->countFilter = $countFilter;
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
        $filter = $request->query->get('filter');
        if (null === $filter) {
            throw new BadRequestHttpException('Filter has to be object or `all` value');
        }
        try {
            if ('all' === $filter) {
                $count = $this->countFilter->filter(new BatchActionFilterDisabled());

                return new SuccessResponse(['count' => $count]);
            }
            /** @var BatchActionFilterFormModel $data */
            $data = $this->normalizer->denormalize(
                $request->query->get('filter') ?? [],
                BatchActionFilterFormModel::class
            );
            $violations = $this->validator->validate($data);
            if (0 !== $violations->count()) {
                throw new ViolationsHttpException($violations);
            }
            $filter = $this->factory->create($data);
            $count = $this->countFilter->filter($filter);

            return new SuccessResponse(['count' => $count]);
        } catch (DenoralizationException $exception) {
            throw new BadRequestHttpException('Invalid query filter');
        }
    }
}
