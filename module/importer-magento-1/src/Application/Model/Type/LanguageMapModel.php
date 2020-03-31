<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class LanguageMapModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="Store view is to long, It should have {{ limit }} character or less.")
     */
    public ?string $store;

    /**
     * @var Language|null
     *
     * @Assert\NotBlank()
     */
    public ?Language $language;

    /**
     * @param string|null   $store
     * @param Language|null $language
     */
    public function __construct(string $store = null, Language $language = null)
    {
        $this->store = $store;
        $this->language = $language;
    }
}
