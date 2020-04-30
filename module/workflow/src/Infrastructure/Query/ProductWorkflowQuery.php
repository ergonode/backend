<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Query;

use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\Service\StatusCalculationService;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ProductWorkflowQuery
{
    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $workflowProvider;

    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $statusRepository;

    /**
     * @var StatusCalculationService
     */
    private StatusCalculationService $service;

    /**
     * @param WorkflowProvider          $workflowProvider
     * @param StatusRepositoryInterface $statusRepository
     * @param StatusCalculationService  $service
     */
    public function __construct(
        WorkflowProvider $workflowProvider,
        StatusRepositoryInterface $statusRepository,
        StatusCalculationService $service
    ) {
        $this->workflowProvider = $workflowProvider;
        $this->statusRepository = $statusRepository;
        $this->service = $service;
    }

    /**
     * @param AbstractProduct $product
     * @param Language        $language
     *
     * @return array
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getQuery(AbstractProduct $product, Language $language): array
    {
        $code = new AttributeCode(StatusSystemAttribute::CODE);
        $result = [];
        if ($product->hasAttribute($code)) {
            $workflow = $this->workflowProvider->provide();
            /** @var StringValue $value */
            $value = $product->getAttribute($code)->getValue();
            $value = reset($value);
            $statusCode = new StatusCode($value);
            $status = $this->statusRepository->load(StatusId::fromCode($statusCode->getValue()));
            Assert::notNull($status);
            $result['status'] = [
                'attribute_id' => AttributeId::fromKey((new AttributeCode(StatusSystemAttribute::CODE))->getValue()),
                'name' => $status->getName()->get($language),
                'code' => $statusCode,
                'color' => $status->getColor(),
            ];

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
