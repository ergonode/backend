<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Request\ParamConverter;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

class ChannelParamConverter implements ParamConverterInterface
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $parameter = $request->get('channel');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "channel" is missing');
        }

        if (!ChannelId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid channel ID format');
        }

        $entity = $this->repository->load(new ChannelId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Channel by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractChannel::class === $configuration->getClass();
    }
}
