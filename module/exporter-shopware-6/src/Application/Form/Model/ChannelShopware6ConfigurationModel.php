<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Form\Model;

use Ergonode\Category\Infrastructure\Validator\CategoryTreeExists;

/**
 */
class ChannelShopware6ConfigurationModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @CategoryTreeExists()
     */
    public ?string $categoryTreeId = null;
}
