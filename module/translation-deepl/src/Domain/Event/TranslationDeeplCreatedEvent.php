<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\TranslationDeepl\Domain\Entity\TranslationDeeplId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TranslationDeeplCreatedEvent implements DomainEventInterface
{
    /**
     * @var TranslationDeeplId
     *
     * @JMS\Type("Ergonode\TranslationDeepl\Domain\Entity\TranslationDeeplId")
     */
    private $id;

    /**
     * @var string
     */
    private $text;
    /**
     * @var string
     */
    private $translation;
    /**
     * @var array
     */
    private $configuration;


    public function __construct(TranslationDeeplId $id, string $text, string $translation, array $configuration)
    {
        $this->id = $id;
        $this->text = $text;
        $this->translation = $translation;
        $this->configuration = $configuration;
    }

    /**
     * @return TranslationDeeplId
     */
    public function getId(): TranslationDeeplId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getTranslation(): string
    {
        return $this->translation;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
