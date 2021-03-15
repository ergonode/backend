<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Request\ParamConverter;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImportParamConverter implements ParamConverterInterface
{
    private ImportRepositoryInterface $importRepository;

    public function __construct(ImportRepositoryInterface $importRepository)
    {
        $this->importRepository = $importRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $parameter = $request->get('import');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "import" is missing');
        }

        if (!ImportId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid import ID');
        }

        $entity = $this->importRepository->load(new ImportId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Import by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Import::class === $configuration->getClass();
    }
}
