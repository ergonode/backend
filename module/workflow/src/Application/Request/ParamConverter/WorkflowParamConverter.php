<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Request\ParamConverter;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class WorkflowParamConverter implements ParamConverterInterface
{
    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $provider;

    /**
     * @param WorkflowProvider $provider
     */
    public function __construct(WorkflowProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $entity = $this->provider->provide();

        if (null === $entity) {
            throw new NotFoundHttpException('Default Workflow not found');
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Workflow::class === $configuration->getClass();
    }
}
