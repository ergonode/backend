<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Request\ParamConverter;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 */
class ExportProfileParamConverter implements ParamConverterInterface
{
    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

    /**
     * ExportProfileParamConverter constructor.
     *
     * @param ExportProfileRepositoryInterface $repository
     */
    public function __construct(ExportProfileRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration):void
    {
        $parameter = $request->get('exportProfile');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "exportProfile" is missing');
        }

        if (!ExportProfileId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid Export Profile Id');
        }

        $entity = $this->repository->load(new ExportProfileId($parameter));

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractExportProfile::class === $configuration->getClass();
    }
}
