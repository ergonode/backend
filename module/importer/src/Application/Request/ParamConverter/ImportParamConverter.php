<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Request\ParamConverter;

use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ImportParamConverter
 */
class ImportParamConverter implements ParamConverterInterface
{
    /**
     * @var ImportRepositoryInterface
     */
    private $importRepository;

    /**
     * @param ImportRepositoryInterface $importRepository
     */
    public function __construct(ImportRepositoryInterface $importRepository)
    {
        $this->importRepository = $importRepository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $attribute = $request->get('import');


        if (null === $attribute) {
            throw new BadRequestHttpException('Route attribute is missing');
        }

        if (!ImportId::isValid($attribute)) {
            throw new BadRequestHttpException('Invalid uuid format');
        }

        $import = $this->importRepository->load(new ImportId($attribute));

        if (null === $import) {
            throw new NotFoundHttpException(\sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $import);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractImport::class === $configuration->getClass();
    }
}
