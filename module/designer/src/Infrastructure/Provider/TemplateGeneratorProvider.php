<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Provider;

use Ergonode\Designer\Infrastructure\Exception\TemplateGeneratorException;
use Ergonode\Designer\Infrastructure\Generator\TemplateGeneratorInterface;

/**
 */
class TemplateGeneratorProvider
{
    /**
     * @var TemplateGeneratorInterface[]
     */
    private $generators;

    /**
     * @param TemplateGeneratorInterface ...$generators
     */
    public function __construct(TemplateGeneratorInterface... $generators)
    {
        $this->generators = $generators;
    }

    /**
     * @param string $code
     *
     * @return TemplateGeneratorInterface
     *
     * @throws TemplateGeneratorException
     */
    public function provide(string $code): TemplateGeneratorInterface
    {
        foreach ($this->generators as $generator) {
            if ($generator->getCode() === $code) {
                return $generator;
            }
        }

        throw new TemplateGeneratorException(sprintf('Can\'t find template generator for code %s', $code));
    }
}
