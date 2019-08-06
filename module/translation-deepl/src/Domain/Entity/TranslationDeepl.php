<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Domain\Entity;


use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\TranslationDeepl\Domain\Event\TranslationDeeplCreatedEvent;

/**
 */
class TranslationDeepl extends AbstractAggregateRoot
{
    /**
     * @var TranslationDeeplId
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

    /**
     * TranslationDeepl constructor.
     * @param TranslationDeeplId $id
     * @param string $text
     * @param string $translation
     * @param array $configuration
     */
    public function __construct(TranslationDeeplId $id, string $text, string $translation, array $configuration)
    {
        $this->apply(new TranslationDeeplCreatedEvent($id, $text, $translation, $configuration));
    }

    /**
     * @return AbstractId
     */
    public function getId(): AbstractId
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
    public function getConfigruation(): array
    {
        return $this->configuration;
    }

    /**
     * @param TranslationDeeplCreatedEvent $event
     */
    protected function applyTranslationDeeplCreatedEvent(TranslationDeeplCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->text = $event->getText();
        $this->translation = $event->getTranslation();
        $this->configuration = $event->getConfiguration();
    }
}
