<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Validator;

use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Product\Application\Validator\ProductExists;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Workflow\Domain\Provider\WorkflowProviderInterface;
use Ergonode\Workflow\Infrastructure\Query\ProductWorkflowQuery;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StatusAvailableValidator extends ConstraintValidator
{
    private EventStoreManagerInterface $manager;

    private ProductWorkflowQuery $query;

    private WorkflowProviderInterface $workflowProvider;

    public function __construct(
        EventStoreManagerInterface $manager,
        ProductWorkflowQuery $query,
        WorkflowProviderInterface $workflowProvider
    ) {
        $this->manager = $manager;
        $this->query = $query;
        $this->workflowProvider = $workflowProvider;
    }

    /**
     * @param mixed                    $value
     * @param ProductExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof StatusAvailable) {
            throw new UnexpectedTypeException($constraint, StatusAvailable::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!ProductId::isValid($value)) {
            return;
        }
        $aggregate = $this->manager->load($constraint->aggregateId);

        if (!$aggregate instanceof AbstractProduct) {
            $this->context->buildViolation($constraint->messageNotProduct)
                ->setParameter('{{ value }}', (string) $constraint->aggregateId)
                ->addViolation();
        }

        $workflow = $this->workflowProvider->provide($constraint->language);
        $statusIds = $this->query->getAvailableStatuses($aggregate, $workflow, $constraint->language);

        if (!in_array($value, $statusIds, true)) {
            $this->context->buildViolation($constraint->messageNotAvailableStatus)
                ->addViolation();
        }
    }
}
