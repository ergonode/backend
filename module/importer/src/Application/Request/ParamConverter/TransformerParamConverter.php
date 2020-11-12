<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Request\ParamConverter;

use Ergonode\Importer\Domain\Entity\Transformer;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Domain\Repository\TransformerRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TransformerParamConverter implements ParamConverterInterface
{
    private TransformerRepositoryInterface $transformerRepository;

    public function __construct(TransformerRepositoryInterface $transformerRepository)
    {
        $this->transformerRepository = $transformerRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('transformer');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "transformer" is missing');
        }

        if (!TransformerId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid transformer ID');
        }

        $entity = $this->transformerRepository->load(new TransformerId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Transformer by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Transformer::class === $configuration->getClass();
    }
}
