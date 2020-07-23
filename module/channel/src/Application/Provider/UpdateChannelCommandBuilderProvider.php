<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Provider;

/**
 */
class UpdateChannelCommandBuilderProvider
{
    /**
     * @var UpdateChannelCommandBuilderInterface[]
     */
    private array $builders;

    /**
     * @param UpdateChannelCommandBuilderInterface ...$builders
     */
    public function __construct(UpdateChannelCommandBuilderInterface ...$builders)
    {
        $this->builders = $builders;
    }

    /**
     * @param string $type
     *
     * @return UpdateChannelCommandBuilderInterface
     */
    public function provide(string $type): UpdateChannelCommandBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supported($type)) {
                return $builder;
            }
        }

        throw new \RuntimeException(sprintf('Can\' find command builder for "%s" channel type', $type));
    }
}
