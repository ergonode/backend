<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Query\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\GetProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
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
     * @var StatusCalculationService
     */
    private $service;

    /**
     * WorkflowGetProductQueryDecorator constructor.
     *
     * @param GetProductQueryInterface    $query
     * @param WorkflowRepositoryInterface $workflowRepository
     * @param ProductRepositoryInterface  $productRepository
     * @param StatusCalculationService    $service
     */
    public function __construct(
        GetProductQueryInterface $query,
        WorkflowRepositoryInterface $workflowRepository,
        ProductRepositoryInterface $productRepository,
        StatusCalculationService $service
    ) {
        $this->query = $query;
        $this->workflowRepository = $workflowRepository;
        $this->productRepository = $productRepository;
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
        $product = $this->productRepository->load($productId);
        Assert::notNull($workflow);
        Assert::notNull($product);
        $result = $this->query->query($productId, $language);
        if (isset($result['attributes'][AbstractProduct::STATUS])) {
            $status = new StatusCode($result['attributes'][AbstractProduct::STATUS]);
            $transitions = $workflow->getTransitionsFromStatus($status);
            $result['status'] = $status;
            foreach ($transitions as $transition) {
                $result['workflow'] = [];
                if ($this->service->available($transition, $product)) {
                    $result['workflow'][] = [
                        'name' => $transition->getName()->get($language),
                        'status' => $transition->getDestination(),
                        '_links' => [
                            'update' => [
                                'href' => 'change product status url if needed',
                            ],
                        ],
                    ];
                }
            }
        }

        return $result;
    }
}
