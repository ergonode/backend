<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

class DownloadHeaderModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="Key is to long, It should have {{ limit }} character or less.")
     */
    public ?string $key;

    /**
     * @Assert\NotNull()
     */
    public ?string $value;

    public function __construct(?string $key = null, ?string $value = null)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
