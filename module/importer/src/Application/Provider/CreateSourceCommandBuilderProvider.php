<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Provider;

/**
 */
class CreateSourceCommandBuilderProvider
{
    /**
     * @var CreateSourceCommandBuilderInterface[]
     */
    private array $builders;

    /**
     * @param CreateSourceCommandBuilderInterface ...$builders
     */
    public function __construct(CreateSourceCommandBuilderInterface ...$builders)
    {
        $this->builders = $builders;
    }

    /**
     * @param string $type
     *
     * @return CreateSourceCommandBuilderInterface
     */
    public function provide(string $type): CreateSourceCommandBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supported($type)) {
                return $builder;
            }
        }

        throw new \RuntimeException(sprintf('Can\' find command builder for "%s" source type', $type));
    }
}
