<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Request\ParamConverter;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SourceParamConverter implements ParamConverterInterface
{
    private SourceRepositoryInterface $sourceRepository;

    public function __construct(SourceRepositoryInterface $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $parameter = $request->get('source');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "source" is missing');
        }

        if (!SourceId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid Source ID');
        }

        $entity = $this->sourceRepository->load(new SourceId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Source by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractSource::class === $configuration->getClass();
    }
}
