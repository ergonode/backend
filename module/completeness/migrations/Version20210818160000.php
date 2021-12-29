<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Completeness\Domain\Entity\Attribute\CompletenessSystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ramsey\Uuid\Uuid;

class Version20210818160000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $label = Uuid::uuid4()->toString();
        $uuid = Uuid::uuid5('eb5fa5eb-ecda-4ff6-ac91-9ac817062635', CompletenessSystemAttribute::CODE)->toString();
        $attributeId = new AttributeId($uuid);

        $eventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => AttributeCreatedEvent::class,
            ]
        )->fetchOne();
        $recorded_at = new \DateTime('now');
        $payload = json_encode(
            [
                "id" => $attributeId->getValue(),
                "code" => CompletenessSystemAttribute::CODE,
                "hint" => [],
                "type" => CompletenessSystemAttribute::TYPE,
                "label" => ["en_GB" => "Completeness", "pl_PL" => "Kompletność"],
                "scope" => "local",
                "system" => true,
                "parameters" => [],
                "placeholder" => [],
            ],
            JSON_UNESCAPED_UNICODE
        );

        $this->addSql(
            'INSERT INTO attribute (id, type, code, label, placeholder, hint, scope, system) VALUES (?,?,?,?,?,?,?,?)',
            [
                $attributeId->getValue(),
                CompletenessSystemAttribute::TYPE,
                CompletenessSystemAttribute::CODE,
                $label,
                Uuid::uuid4()->toString(),
                Uuid::uuid4()->toString(),
                'local',
                true,
            ]
        );

        $this->addSql(
            'INSERT INTO value_translation (id, value_id, language, value) VALUES (?,?,?,?)',
            [
                Uuid::uuid4()->toString(),
                $label,
                'pl_PL',
                'Kompletność',
            ]
        );

        $this->addSql(
            'INSERT INTO value_translation (id, value_id, language, value) VALUES (?,?,?,?)',
            [
                Uuid::uuid4()->toString(),
                $label,
                'en_GB',
                'Completeness',
            ]
        );

        $this->addSql(
            'INSERT INTO event_store (aggregate_id, sequence, event_id, payload, recorded_at) VALUES (?,?,?,?,?)',
            [
                $attributeId->getValue(),
                1,
                $eventId,
                $payload,
                $recorded_at->format('Y-m-d H:i:s.u'),
            ]
        );

        $this->addSql(
            'INSERT INTO event_store_class (aggregate_id, class) VALUES (?,?)',
            [
                $attributeId->getValue(),
                CompletenessSystemAttribute::class,
            ]
        );

        $this->addSql(
            'INSERT INTO event_store_snapshot (
                                  aggregate_id,
                                  sequence, 
                                  payload,
                                  recorded_by, 
                                  recorded_at
                                  ) VALUES (?,?,?,?,?)',
            [
                $attributeId->getValue(),
                1,
                $payload,
                Uuid::uuid4()->toString(),
                $recorded_at->format('Y-m-d H:i:s.u'),
            ]
        );
    }
}
