<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\ValueObject;

class TranslatableString implements \IteratorAggregate
{
    /**
     * @var string[]
     */
    private array $translations;

    /**
     * @param array $translations
     */
    public function __construct(array $translations = [])
    {
        $this->translations = [];
        foreach ($translations as $language => $translation) {
            $translation = !is_null($translation) ? (string) $translation : null;
            $this->translations = $this->merge($this->translations, new Language($language), $translation);
        }
    }

    /**
     * @return TranslatableString
     */
    public static function create(Language $language, string $value): self
    {
        return new self([$language->getCode() => $value]);
    }

    /**
     * @return TranslatableString
     */
    public function add(Language $language, string $value): self
    {
        return new self($this->merge($this->translations, $language, $value));
    }

    /**
     * @return TranslatableString
     */
    public function change(Language $language, string $value): self
    {
        return new self($this->merge($this->translations, $language, $value));
    }

    public function has(Language $language): bool
    {
        return isset($this->translations[$language->getCode()]);
    }

    /**
     * @return TranslatableString
     */
    public function remove(Language $language): self
    {
        $new = $this->translations;
        unset($new[$language->getCode()]);

        return new self($new);
    }

    public function get(Language $language): ?string
    {
        return $this->translations[$language->getCode()] ?? null;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->translations);
    }


    /**
     * @return string[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function isEqual(TranslatableString $string): bool
    {
        return count(array_diff_assoc($string->getTranslations(), $this->translations)) === 0
            && count(array_diff_assoc($this->translations, $string->getTranslations())) === 0;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function merge(array $array, Language $language, ?string $value): array
    {
        return array_merge($array, [$language->getCode() => $value]);
    }
}
