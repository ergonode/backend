<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Command;

use Ergonode\Importer\Domain\Command\CreateSourceCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\Core\Infrastructure\Service\Header;
use Webmozart\Assert\Assert;

class CreateErgonodeZipSourceCommand implements CreateSourceCommandInterface
{
    private SourceId $id;

    private string $name;

    private array $import;

    /**
     * @var Header[]
     */
    private array $headers;

    /**
     * @param string[] $import
     * @param Header[] $headers
     */
    public function __construct(
        SourceId $id,
        string $name,
        array $import = [],
        array $headers = []
    ) {
        Assert::allString($import);
        Assert::allIsInstanceOf($headers, Header::class);

        $this->id = $id;
        $this->name = $name;
        $this->import = $import;
        $this->headers = $headers;
    }

    public function getId(): SourceId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImport(): array
    {
        return $this->import;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
