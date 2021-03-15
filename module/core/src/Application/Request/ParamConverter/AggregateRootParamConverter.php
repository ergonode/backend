<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Request\ParamConverter;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

class AggregateRootParamConverter implements ParamConverterInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $name = $configuration->getName();

        $parameter = $request->get($name);
        Assert::notNull($parameter);
        Assert::string($parameter);

        if (!AggregateId::isValid($parameter)) {
            throw new BadRequestHttpException(sprintf('Invalid "%s" ID', $name));
        }
        $resource = $this->manager->load(new AggregateId($parameter));

        if (null === $resource) {
            throw new NotFoundHttpException(sprintf('"%s" by ID "%s" not found', $name, $parameter));
        }
        $class = $configuration->getClass();

        if (!$resource instanceof $class) {
            throw new NotFoundHttpException(sprintf('There is no such %s resource', $name));
        }

        $request->attributes->set($configuration->getName(), $resource);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return is_subclass_of($configuration->getClass(), AbstractAggregateRoot::class);
    }
}
