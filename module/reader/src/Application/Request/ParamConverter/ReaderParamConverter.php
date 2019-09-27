<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Application\Request\ParamConverter;

use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class ReaderParamConverter implements ParamConverterInterface
{
    /**
     * @var ReaderRepositoryInterface
     */
    private $readerRepository;

    /**
     * @param ReaderRepositoryInterface $readerRepository
     */
    public function __construct(ReaderRepositoryInterface $readerRepository)
    {
        $this->readerRepository = $readerRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('reader');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "reader" is missing');
        }

        if (!ReaderId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid reader ID');
        }

        $entity = $this->readerRepository->load(new ReaderId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Reader by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Reader::class === $configuration->getClass();
    }
}
