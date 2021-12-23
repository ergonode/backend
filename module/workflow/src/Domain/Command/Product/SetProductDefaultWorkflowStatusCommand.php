<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Product;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Domain\ValueObject\Language;

class SetProductDefaultWorkflowStatusCommand
{
    private ProductId $id;

    private Language $language;

    public function __construct(ProductId $id, Language $language)
    {
        $this->id = $id;
        $this->language = $language;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
