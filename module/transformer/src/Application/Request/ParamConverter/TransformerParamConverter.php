<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\Request\ParamConverter;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class TransformerParamConverter implements ParamConverterInterface
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $transformerRepository;

    /**
     * @param TransformerRepositoryInterface $transformerRepository
     */
    public function __construct(TransformerRepositoryInterface $transformerRepository)
    {
        $this->transformerRepository = $transformerRepository;
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
        $attribute = $request->get('transformer');


        if (null === $attribute) {
            throw new BadRequestHttpException('Route attribute is missing');
        }

        if (!TransformerId::isValid($attribute)) {
            throw new BadRequestHttpException('Invalid uuid format');
        }

        $import = $this->transformerRepository->load(new TransformerId($attribute));

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
        return Transformer::class === $configuration->getClass();
    }
}
