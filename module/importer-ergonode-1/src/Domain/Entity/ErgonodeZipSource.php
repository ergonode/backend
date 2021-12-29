<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Entity;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Service\Header;

class ErgonodeZipSource extends AbstractSource
{
    public const TYPE = 'ergonode-zip';

    public const DELIMITER = ',';
    public const ENCLOSURE = '"';
    public const ESCAPE = '\\';

    public const PRODUCTS = 'products';
    public const CATEGORIES = 'categories';
    public const TEMPLATES = 'templates';
    public const ATTRIBUTES = 'attributes';
    public const MULTIMEDIA = 'multimedia';
    public const OPTIONS = 'options';

    public const STEPS = [
        self::PRODUCTS,
        self::CATEGORIES,
        self::TEMPLATES,
        self::ATTRIBUTES,
        self::MULTIMEDIA,
        self::OPTIONS,
    ];

    private array $import;

    /**
     * @var Header[]
     */
    private array $headers;

    /**
     * @param string[] $imports
     * @param Header[] $headers
     */
    public function __construct(
        SourceId $id,
        string $name,
        array $imports = [],
        array $headers = []
    ) {
        parent::__construct($id, $name);

        $this->setImport($imports);
        $this->setHeaders($headers);
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getDelimiter(): string
    {
        return self::DELIMITER;
    }

    public function getEnclosure(): string
    {
        return self::ENCLOSURE;
    }

    public function getEscape(): string
    {
        return self::ESCAPE;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string[] $import
     */
    public function setImport(array $import): void
    {
        Assert::allString($import);
        Assert::allOneOf($import, static::STEPS);

        $this->import = $import;
    }

    /**
     * @param Header[] $headers
     */
    public function setHeaders(array $headers): void
    {
        Assert::allIsInstanceOf($headers, Header::class);
        $this->headers = $headers;
    }

    public function import(string $step): bool
    {
        if (array_key_exists($step, array_flip($this->import))) {
            return true;
        }

        return false;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getSteps(): array
    {
        return static::STEPS;
    }
}
