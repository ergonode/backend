<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Request\ParamConverter;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AttributeParamConverter implements ParamConverterInterface
{
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        if ($configuration->getName()) {
            $parameter = $request->get($configuration->getName());
        } else {
            $parameter = $request->get('attribute');
        }

        if (null === $parameter) {
            throw new BadRequestHttpException(sprintf('Request parameter "%s" is missing', $parameter));
        }

        if (!AttributeId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid attribute ID');
        }

        $entity = $this->attributeRepository->load(new AttributeId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Attribute by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractAttribute::class === $configuration->getClass();
    }
}
