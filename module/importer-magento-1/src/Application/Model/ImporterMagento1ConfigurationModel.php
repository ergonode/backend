<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model;

use Ergonode\ImporterMagento1\Application\Model\Type\LanguageMapModel;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ImporterMagento1ConfigurationModel
{
    /**
     * @var string|null
     *
     *@Assert\Length(min=2)
     */
    public ?string $test;

    /**
     * @var LanguageMapModel[]
     *
     * @Assert\Valid()
     */
    public array $languages;

    /**
     */
    public function __construct()
    {
        $this->languages = [];
        $this->test = null;
    }
}
