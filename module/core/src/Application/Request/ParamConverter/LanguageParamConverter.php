<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Request\ParamConverter;

use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LanguageParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $parameter = $request->get($configuration->getName());

        if (null === $parameter) {
            throw new BadRequestHttpException("Request parameter '{$configuration->getName()}' is missing");
        }

        if (!Language::isValid($parameter)) {
            throw new BadRequestHttpException(sprintf('Language code "%s" is invalid', $parameter));
        }

        $request->attributes->set($configuration->getName(), new Language($parameter));

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Language::class === $configuration->getClass();
    }
}
