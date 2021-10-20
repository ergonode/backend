<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Core\Infrastructure\Service\Header;
use Webmozart\Assert\Assert;

class ImportMultimediaFromWebCommand implements ImporterCommandInterface
{
    private ImportLineId $id;

    private ImportId $importId;

    private string $url;

    private string $name;

    private array $alt;

    /**
     * @var Header[]
     */
    private array $headers;

    /**
     * @param Header[] $headers
     */
    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $url,
        string $name,
        array $alt = [],
        array $headers = []
    ) {
        Assert::allIsInstanceOf($headers, Header::class);

        $this->id = $id;
        $this->importId = $importId;
        $this->url = $url;
        $this->name = $name;
        $this->alt = $alt;
        $this->headers = $headers;
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlt(): array
    {
        return $this->alt;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
