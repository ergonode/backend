<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Application\Request\ParamConverter;

use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\PostGridConfiguration;
use Ergonode\Grid\GridConfigurationInterface;

class GridConfigurationParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $requestGridConfiguration = new PostGridConfiguration($request);
        } else {
            $requestGridConfiguration = new RequestGridConfiguration($request);
        }

        $request->attributes->set($configuration->getName(), $requestGridConfiguration);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        $class = $configuration->getClass();

        return
            GridConfigurationInterface::class === $class
            || PostGridConfiguration::class === $class
            || RequestGridConfiguration::class === $class;
    }
}
