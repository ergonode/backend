<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Request\ParamConverter;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AttributeParamConverter
 */
class AttributeParamConverter implements ParamConverterInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $attribute = $request->get('attribute');


        if (null === $attribute) {
            throw new BadRequestHttpException('Route attribute is missing');
        }

        if (!AttributeId::isValid($attribute)) {
            throw new BadRequestHttpException('Invalid uuid format');
        }

        $attribute = $this->attributeRepository->load(new AttributeId($attribute));

        if (null === $attribute) {
            throw new NotFoundHttpException(\sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $attribute);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractAttribute::class === $configuration->getClass();
    }
}
