<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Request\ParamConverter;

use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Core\Domain\ValueObject\Language;

class AbstractWorkflowParamConverter implements ParamConverterInterface
{
    private WorkflowProvider $provider;

    public function __construct(WorkflowProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $entity = $this->provider->provide(new Language('pl_PL'));

        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractWorkflow::class === $configuration->getClass();
    }
}
