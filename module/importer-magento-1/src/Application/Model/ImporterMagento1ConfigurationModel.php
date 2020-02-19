<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model;

use Ergonode\ImporterMagento1\Application\Model\Type\LanguageMapModel;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ImporterMagento1ConfigurationModel
{
    /**
     * @var string|null
     *
     *@Assert\Length(min=2)
     */
    public ?string $name;

    /**
     * @var string|null
     *
     *@Assert\NotBlank()
     *@Assert\Url()
     */
    public ?string $host;

    /**
     * @var Language|null
     */
    public ?Language $defaultLanguage;

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
        $this->name = null;
        $this->host = null;
        $this->defaultLanguage = null;
    }
}
