<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Request\ParamConverter;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

/**
 */
class AggregateRootParamConverter implements ParamConverterInterface
{
    /**
     * @var EventStoreManager
     */
    private EventStoreManager $manager;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $provider;

    /**
     * @param EventStoreManager $manager
     * @param WorkflowProvider  $provider
     */
    public function __construct(EventStoreManager $manager, WorkflowProvider $provider)
    {
        $this->manager = $manager;
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $name = $configuration->getName();
        $class = $configuration->getClass();
        if (null === $name) {
            throw new BadRequestHttpException('Param Converter argument \'Name\'is missing');
        }

        if (AbstractWorkflow::class === $class) {
            $entity = $this->provider->provide();
        } else {
            $parameter = $request->get($name);

            if (null === $parameter) {
                throw new BadRequestHttpException(sprintf('Request parameter "%s" is missing', $name));
            }

            if (!AggregateId::isValid($parameter)) {
                throw new BadRequestHttpException(sprintf('Invalid "%s" ID', $name));
            }
            $entity = $this->manager->load(new AggregateId($parameter));

            if (null === $entity) {
                throw new NotFoundHttpException(sprintf('"%s" by ID "%s" not found', $name, $parameter));
            }

            if (!$entity instanceof $class) {
                throw new BadRequestHttpException(sprintf('Entity is not instance of %s', $class));
            }
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return is_subclass_of($configuration->getClass(), AbstractAggregateRoot::class);
    }
}
