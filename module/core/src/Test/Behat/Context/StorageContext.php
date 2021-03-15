<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;

class StorageContext implements Context
{
    private const SEPARATOR = '@';

    /**
     * @var array
     */
    private static array $storage = [];

    /**
     * @var array
     */
    private static array $tags = [];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->addDefinition('random_uuid', Uuid::uuid4());
        $this->addDefinition('random_code', str_replace('-', '_', Uuid::uuid4()));
        $this->addDefinition('random_md5', md5(Uuid::uuid4()->toString()));
        $this->addDefinition('static_uuid', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa');
        $this->addDefinition('random_symbol', base64_encode(random_bytes(8)));
    }

    /**
     * @param mixed $value
     *
     * @Then remember param :key with value :value
     */
    public function add(string $key, $value): void
    {
        if (is_string($value)) {
            $value = $this->replaceVars($value);
        }

        self::$storage[$key] = $value;
        $this->addTag($key, $value);
    }

    /**
     * @Then delete remembered :key
     */
    public function delete(string $key): void
    {
        if (array_key_exists($key, self::$storage)) {
            unset(self::$storage[$key]);
            $this->deleteTag($key);
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, self::$storage);
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            throw new \RuntimeException(sprintf(
                'Key "%s" not found in storage',
                $key
            ));
        }

        return self::$storage[$key];
    }

    public function replaceVars(string $content): string
    {
        return str_replace(array_keys(self::$tags), array_values(self::$tags), $content);
    }

    /**
     * @param mixed $value
     */
    public function addDefinition(string $key, $value): void
    {
        self::$tags[self::SEPARATOR.self::SEPARATOR.$key.self::SEPARATOR.self::SEPARATOR] = $value;
    }

    /**
     * @param mixed $value
     */
    private function addTag(string $key, $value): void
    {
        self::$tags[self::SEPARATOR.$key.self::SEPARATOR] = $value;
    }

    private function deleteTag(string $key): void
    {
        unset(self::$tags[self::SEPARATOR.$key.self::SEPARATOR]);
    }
}
