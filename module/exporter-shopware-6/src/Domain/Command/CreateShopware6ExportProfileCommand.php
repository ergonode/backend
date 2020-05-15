<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class CreateShopware6ExportProfileCommand implements DomainCommandInterface
{
    /**
     * @var  ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    protected ExportProfileId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $name;
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $host;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $clientId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $clientKey;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @param ExportProfileId $id
     * @param string          $name
     * @param string          $host
     * @param string          $clientId
     * @param string          $clientKey
     * @param Language        $defaultLanguage
     */
    public function __construct(
        ExportProfileId $id,
        string $name,
        string $host,
        string $clientId,
        string $clientKey,
        Language $defaultLanguage
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @return ExportProfileId
     */
    public function getId(): ExportProfileId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }
}
