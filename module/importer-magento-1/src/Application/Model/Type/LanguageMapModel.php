<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;

class LanguageMapModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="Store view is to long, It should have {{ limit }} character or less.")
     */
    public ?string $store;

    /**
     * @Assert\NotBlank()
     */
    public ?Language $language;

    public function __construct(string $store = null, Language $language = null)
    {
        $this->store = $store;
        $this->language = $language;
    }
}
