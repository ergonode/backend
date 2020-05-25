<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Model;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class Record
{
    /**
     * @var ValueInterface[]
     *
     * @JMS\Type(array<string, string>)
     */
    private array $elements;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type(array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>)
     */
    private array $values;

    /**
     */
    public function __construct()
    {
        $this->elements = [];
        $this->values = [];
    }

    /**
     * @param string        $name
     * @param string        $value
     * @param Language|null $language
     */
    public function set(string $name, string $value, ?Language $language = null): void
    {
        $code = $language ? $language->getCode() : null;
        $this->elements[$name][$code] = $value;
    }

    /**
     * @param string        $name
     *
     * @param Language|null $language
     *
     * @return bool
     */
    public function has(string $name, ?Language $language = null): bool
    {
        $code = $language ? $language->getCode() : null;

        return array_key_exists($name, $this->elements) && array_key_exists($code, $this->elements[$name]);
    }

    /**
     * @param string        $name
     *
     * @param Language|null $language
     *
     * @return string
     */
    public function get(string $name, ?Language $language = null): string
    {
        if ($this->has($name, $language)) {
            $code = $language ? $language->getCode() : null;

            return $this->elements[$name][$code];
        }

        throw new \InvalidArgumentException(\sprintf('Record haven\'t field %s', $name));
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasTranslation(string $name): bool
    {
        return array_key_exists($name, $this->elements);
    }

    /**
     * @param string $name
     *
     * @return TranslatableString
     */
    public function getTranslation(string $name): TranslatableString
    {
        if ($this->hasTranslation($name)) {
            return new TranslatableString($this->elements[$name]);
        }

        throw new \InvalidArgumentException(\sprintf('Record haven\'t field %s', $name));
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasValue(string $name): bool
    {
        if (array_key_exists($name, $this->values)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return ValueInterface|null
     */
    public function getValue(string $name): ?ValueInterface
    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        throw new \InvalidArgumentException(\sprintf('Record haven\'t value %s', $name));
    }

    /**
     * @param string              $code
     * @param ValueInterface|null $value
     */
    public function setValue(string $code, ?ValueInterface $value): void
    {
        $this->values[$code] = $value;
    }

    /**
     * @return ValueInterface[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
