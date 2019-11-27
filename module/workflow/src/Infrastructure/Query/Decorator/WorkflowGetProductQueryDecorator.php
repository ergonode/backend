<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Query\Decorator;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\GetProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusAttribute;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Service\StatusCalculationService;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Webmozart\Assert\Assert;

/**
 */
class WorkflowGetProductQueryDecorator implements GetProductQueryInterface
{
    /**
     * @var GetProductQueryInterface
     */
    private $query;

    /**
     * @var WorkflowRepositoryInterface
     */
    private $workflowRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StatusRepositoryInterface
     */
    private $statusRepository;

    /**
     * @var StatusCalculationService
     */
    private $service;

    /**
     * @param GetProductQueryInterface    $query
     * @param WorkflowRepositoryInterface $workflowRepository
     * @param ProductRepositoryInterface  $productRepository
     * @param StatusRepositoryInterface   $statusRepository
     * @param StatusCalculationService    $service
     */
    public function __construct(
        GetProductQueryInterface $query,
        WorkflowRepositoryInterface $workflowRepository,
        ProductRepositoryInterface $productRepository,
        StatusRepositoryInterface $statusRepository,
        StatusCalculationService $service
    ) {
        $this->query = $query;
        $this->workflowRepository = $workflowRepository;
        $this->productRepository = $productRepository;
        $this->statusRepository = $statusRepository;
        $this->service = $service;
    }

    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return array
     * @throws \Exception
     */
    public function query(ProductId $productId, Language $language): array
    {
        $workflow = $this->workflowRepository->load(WorkflowId::fromCode(Workflow::DEFAULT));
        Assert::notNull($workflow);

        $product = $this->productRepository->load($productId);
        Assert::notNull($product);

        $result = $this->query->query($productId, $language);
        if (isset($result['attributes'][StatusAttribute::CODE])) {
            /** @var ValueInterface $value */
            $value = $result['attributes'][StatusAttribute::CODE];
            $statusCode = new StatusCode($value->getValue());
            $status = $this->statusRepository->load(StatusId::fromCode($statusCode));
            Assert::notNull($status);
            $result['status'] = [
                'attribute_id' => AttributeId::fromKey(new AttributeCode(StatusAttribute::CODE)),
                'name' => $status->getName()->get($language),
                'code' => $statusCode,
                'color' => $status->getColor(),
            ];

            /** @var Transition[] $transitions */
            $transitions = $workflow->getTransitionsFromStatus($statusCode);
            $result['workflow'] = [];
            foreach ($transitions as $transition) {
                if ($this->service->available($transition, $product)) {
                    $destinationStatus = $this->statusRepository->load(StatusId::fromCode($transition->getTo()));
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
