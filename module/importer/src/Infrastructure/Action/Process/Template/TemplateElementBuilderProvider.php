<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Template;

class TemplateElementBuilderProvider
{
    /**
     * @var TemplateElementBuilderInterface[]
     */
    private array $builders;

    /**
     * @param TemplateElementBuilderInterface[] $builders
     */
    public function __construct(array $builders)
    {
        $this->builders = $builders;
    }

    public function provide(string $type): TemplateElementBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supported($type)) {
                return $builder;
            }
        }

        throw new \RuntimeException(sprintf('can\'t find template element builder for "%s" type', $type));
    }
}
