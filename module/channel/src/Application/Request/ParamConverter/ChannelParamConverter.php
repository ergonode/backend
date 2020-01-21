<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Request\ParamConverter;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class ChannelParamConverter implements ParamConverterInterface
{
    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $repository;

    /**
     * @param ChannelRepositoryInterface $repository
     */
    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
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
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Channel::class === $configuration->getClass();
    }
}
