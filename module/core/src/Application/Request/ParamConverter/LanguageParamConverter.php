<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Request\ParamConverter;

use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 */
class LanguageParamConverter implements ParamConverterInterface
{
    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return void
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $language = $request->get('language');

        if (null === $language) {
            throw new BadRequestHttpException('Route attribute is missing');
        }

        if (!Language::isValid($language)) {
            throw new BadRequestHttpException('Invalid language code');
        }

        $request->attributes->set($configuration->getName(), new Language($language));
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Language::class === $configuration->getClass();
    }
}
