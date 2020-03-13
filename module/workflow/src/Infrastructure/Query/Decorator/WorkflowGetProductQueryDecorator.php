<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Query\Decorator;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\GetProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Service\StatusCalculationService;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;

/**
 */
class WorkflowGetProductQueryDecorator implements GetProductQueryInterface
{
    /**
     * @var GetProductQueryInterface
     */
    private GetProductQueryInterface $query;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $workflowProvider;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $statusRepository;

    /**
     * @var StatusCalculationService
     */
    private StatusCalculationService $service;

    /**
     * @param GetProductQueryInterface   $query
     * @param WorkflowProvider           $workflowProvider
     * @param ProductRepositoryInterface $productRepository
     * @param StatusRepositoryInterface  $statusRepository
     * @param StatusCalculationService   $service
     */
    public function __construct(
        GetProductQueryInterface $query,
        WorkflowProvider $workflowProvider,
        ProductRepositoryInterface $productRepository,
        StatusRepositoryInterface $statusRepository,
        StatusCalculationService $service
    ) {
        $this->query = $query;
        $this->workflowProvider = $workflowProvider;
        $this->productRepository = $productRepository;
        $this->statusRepository = $statusRepository;
        $this->service = $service;
    }

    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return array
     *
     * @throws \Exception
     */
    public function query(ProductId $productId, Language $language): array
    {
        $workflow = $this->workflowProvider->provide();

        $product = $this->productRepository->load($productId);
        Assert::notNull($product);

        $result = $this->query->query($productId, $language);
        if (isset($result['attributes'][StatusSystemAttribute::CODE])) {
            /** @var ValueInterface $value */
            $value = $result['attributes'][StatusSystemAttribute::CODE];
            $statusCode = new StatusCode($value->getValue());
            $status = $this->statusRepository->load(StatusId::fromCode($statusCode->getValue()));
            Assert::notNull($status);
            $result['status'] = [
                'attribute_id' => AttributeId::fromKey((new AttributeCode(StatusSystemAttribute::CODE))->getValue()),
                'name' => $status->getName()->get($language),
                'code' => $statusCode,
                'color' => $status->getColor(),
            ];

            /** @var Transition[] $transitions */
            $transitions = $workflow->getTransitionsFromStatus($statusCode);
            $result['workflow'] = [];
            foreach ($transitions as $transition) {
                if ($this->service->available($transition, $product)) {
                    $destinationStatus = $this->statusRepository->load(
                        StatusId::fromCode($transition->getTo()->getValue())
                    );
                    Assert::notNull($destinationStatus);
                    $result['workflow'][] = [
                        'name' => $destinationStatus->getName()->get($language),
                        'transition' => '',
                        'code' => $destinationStatus->getCode(),
                        'color' => $destinationStatus->getColor(),
                    ];
                }
            }
        }

        return $result;
    }
}
