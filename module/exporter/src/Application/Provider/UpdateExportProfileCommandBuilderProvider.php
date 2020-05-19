<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Provider;

/**
 */
class UpdateExportProfileCommandBuilderProvider
{
    /**
     * @var UpdateExportProfileCommandBuilderInterface[]
     */
    private array $builders;

    /**
     * @param array|UpdateExportProfileCommandBuilderInterface ...$builders
     */
    public function __construct(UpdateExportProfileCommandBuilderInterface ...$builders)
    {
        $this->builders = $builders;
    }

    /**
     * @param string $type
     *
     * @return UpdateExportProfileCommandBuilderInterface
     */
    public function provide(string $type): UpdateExportProfileCommandBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supported($type)) {
                return $builder;
            }
        }
        throw new \RuntimeException(sprintf('Can\' find command builder for "%s" export profile type', $type));
    }
}
