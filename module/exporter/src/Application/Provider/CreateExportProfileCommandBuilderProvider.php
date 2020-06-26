<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Provider;

/**
 */
class CreateExportProfileCommandBuilderProvider
{
    /**
     * @var CreateExportProfileCommandBuilderInterface[]
     */
    private array $builders;

    /**
     * @param CreateExportProfileCommandBuilderInterface ...$builders
     */
    public function __construct(CreateExportProfileCommandBuilderInterface ...$builders)
    {
        $this->builders = $builders;
    }

    /**
     * @param string $type
     *
     * @return CreateExportProfileCommandBuilderInterface
     */
    public function provide(string $type): CreateExportProfileCommandBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supported($type)) {
                return $builder;
            }
        }
        throw new \RuntimeException(sprintf('Can\' find command builder for "%s" export profile type', $type));
    }
}
