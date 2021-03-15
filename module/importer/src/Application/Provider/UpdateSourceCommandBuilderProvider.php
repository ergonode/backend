<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Provider;

class UpdateSourceCommandBuilderProvider
{
    /**
     * @var UpdateSourceCommandBuilderInterface[]
     */
    private array $builders;

    public function __construct(UpdateSourceCommandBuilderInterface ...$builders)
    {
        $this->builders = $builders;
    }

    public function provide(string $type): UpdateSourceCommandBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supported($type)) {
                return $builder;
            }
        }

        throw new \RuntimeException(sprintf('Can\' find command builder for "%s" source type', $type));
    }
}
