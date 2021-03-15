<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Request\ParamConverter;

use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\Channel\Domain\Entity\Export;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExportParamConverter implements ParamConverterInterface
{
    private ExportRepositoryInterface $repository;

    public function __construct(ExportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $parameter = $request->get('export');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "export" is missing');
        }

        if (!ExportId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid Export Id');
        }

        $entity = $this->repository->load(new ExportId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Export by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Export::class === $configuration->getClass();
    }
}
