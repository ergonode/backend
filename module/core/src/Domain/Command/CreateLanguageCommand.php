<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ramsey\Uuid\Uuid;

/**
 */
class CreateLanguageCommand
{
    public const NAMESPACE = 'ab84ce92-3105-4256-bff9-940423dd04e9';
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private $code;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private $active;

    /**
     * CreateLanguageCommand constructor.
     *
     * @param Language $code
     * @param string   $name
     * @param bool     $active
     */
    public function __construct(Language $code, string $name, bool $active)
    {
        $this->id = Uuid::uuid5(self::NAMESPACE, $code);
        $this->code = $code;
        $this->name = $name;
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Language
     */
    public function getCode(): Language
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }
}
