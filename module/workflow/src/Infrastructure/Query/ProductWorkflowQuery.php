<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Query;

use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\Service\StatusCalculationService;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;

class ProductWorkflowQuery
{
    private StatusRepositoryInterface $statusRepository;

    private StatusCalculationService $service;

    private AttributeQueryInterface $attributeQuery;

    private StatusQueryInterface $statusQuery;

    public function __construct(
        StatusRepositoryInterface $statusRepository,
        StatusCalculationService $service,
        AttributeQueryInterface $attributeQuery,
        StatusQueryInterface $statusQuery
    ) {
        $this->statusRepository = $statusRepository;
        $this->service = $service;
        $this->attributeQuery = $attributeQuery;
        $this->statusQuery = $statusQuery;
    }

    /**
     * @return array
     *
     * @throws \ReflectionException
     */
    public function getQuery(
        AbstractProduct $product,
        AbstractWorkflow $workflow,
        Language $language,
        Language $productLanguage
    ): array {
        $code = new AttributeCode(StatusSystemAttribute::CODE);
        $result = [];
        if ($product->hasAttribute($code)) {
            $attributeId = $this->attributeQuery->findAttributeIdByCode($code);
            Assert::notNull($attributeId, sprintf('attribute %s not exists', $attributeId->getValue()));
            $value = $product->getAttribute($code)->getValue();
            $statusId = new StatusId($value[$productLanguage->getCode()]);
            $status = $this->statusRepository->load($statusId);
            Assert::notNull($status, sprintf('status %s not exists', $statusId->getValue()));
            $result['status'] = [
                'attribute_id' => $attributeId->getValue(),
                'id' => $status->getId()->getValue(),
                'name' => $status->getName()->get($language),
                'code' => $status->getCode()->getValue(),
                'color' => $status->getColor(),
            ];

            $transitions = $workflow->getTransitionsFromStatus($statusId);
            $sortedTransitions = $this->sortTransitions($transitions);
            $result['workflow'] = [];
            foreach ($sortedTransitions as $transition) {
                if ($this->service->available($transition, $product)) {
                    $fromStatus = $this->statusRepository->load($transition->getTo());
                    Assert::notNull($fromStatus);
                    $result['workflow'][] = [
                        'id' => $fromStatus->getId()->getValue(),
                        'name' => $fromStatus->getName()->get($language),
                        'code' => $fromStatus->getCode(),
                        'color' => $fromStatus->getColor(),
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * @param Transition[] $transitions
     *
     * @return Transition[]
     */
    private function sortTransitions(array $transitions): array
    {
        $sortedStatusIds = $this->statusQuery->getAllStatusIds();
        $sortedTransitions = [];
        foreach ($transitions as $transition) {
            $position = array_search($transition->getTo()->getValue(), $sortedStatusIds, true);
            $sortedTransitions[$position] = $transition;
        }
        ksort($sortedTransitions);

        return $sortedTransitions;
    }
}
