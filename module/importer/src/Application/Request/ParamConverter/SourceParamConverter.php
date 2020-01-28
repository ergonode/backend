<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Request\ParamConverter;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Entity\Source\SourceId;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class SourceParamConverter implements ParamConverterInterface
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $SourceRepository;

    /**
     * @param SourceRepositoryInterface $SourceRepository
     */
    public function __construct(SourceRepositoryInterface $SourceRepository)
    {
        $this->SourceRepository = $SourceRepository;
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

        $entity = $this->SourceRepository->load(new SourceId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Source by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractSource::class === $configuration->getClass();
    }
}
