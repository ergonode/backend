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
    public const OPTIONS = 'options';

    public const STEPS = [
        self::PRODUCTS,
        self::CATEGORIES,
        self::TEMPLATES,
        self::ATTRIBUTES,
        self::OPTIONS,
    ];

    private array $import;

    public function __construct(
        SourceId $id,
        string $name,
        array $imports = []
    ) {
        parent::__construct($id, $name);
        Assert::allString($imports);

        $this->import = [];
        foreach ($imports as $import) {
            $this->import[] = $import;
        }
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

    public function setImport(array $import): void
    {
        $this->import = $import;
    }

    public function import(string $step): bool
    {
        if (array_key_exists($step, array_flip($this->import))) {
            return true;
        }

        return false;
    }
}
