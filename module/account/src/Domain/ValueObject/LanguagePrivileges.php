<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class LanguagePrivileges
{
    /**
     * @var array
     *
     * @JMS\Type("array<string, array>")
     */
    private array $value;

    /**
     * @param array $value
     */
    public function __construct(array $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(\sprintf('Language Privilege "%s" is incorrect', $value));
        }

        $this->value = $value;
    }

    /**
     * @param array $value
     *
     * @return bool
     */
    public static function isValid(array $value): bool
    {
        if (!array_key_exists('read', $value) || !array_key_exists('edit', $value)) {
            return false;
        }

        return is_array($value['read']) && is_array($value['edit']);
    }

    /**
     * @param LanguagePrivileges $value
     *
     * @return bool
     */
    public function isEqual(LanguagePrivileges $value): bool
    {
        if (!(count($value->getValue()) === count(array_intersect_key($value->getValue(), $this->value)))) {
            return false;
        }
        foreach ($this->getValue() as $type => $item) {
            if (!(
                count(array_diff($value->value[$type], $item)) === 0
                && count(array_diff($item, $value->value[$type])) === 0
            )) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $type
     * @param array  $value
     *
     * @return bool
     */
    public function isEqualByType(string $type, array $value): bool
    {
        $this->typeCheck($type);

        return count(array_diff($this->value[$type], $value)) === 0
            && count(array_diff($value, $this->value[$type])) === 0;
    }

    /**
     * @param string   $type
     * @param Language $language
     *
     * @return bool
     */
    public function existLanguageByType(string $type, Language $language): bool
    {
        $this->typeCheck($type);

        return in_array($language->getCode(), $this->value[$type], true);
    }

    /**
     * @param string   $type
     * @param Language $language
     *
     * @return LanguagePrivileges
     */
    public function removeLanguageByType(string $type, Language $language): self
    {
        $this->typeCheck($type);

        if (($key = array_search($language->getCode(), $this->value[$type], true)) !== false) {
            unset($this->value[$type][$key]);
        }

        return $this;
    }

    /**
     * @param string   $type
     * @param Language $language
     *
     * @return LanguagePrivileges
     */
    public function addLanguageByType(string $type, Language $language): self
    {
        $this->typeCheck($type);

        if (!$this->existLanguageByType($type, $language)) {
            $this->value[$type][] = $language->getCode();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function typeCheck(string $type): bool
    {
        if (!isset($this->value[$type])) {
            throw new \InvalidArgumentException(sprintf('Type: "%s" does not exist', $type));
        }

        return true;
    }
}
