<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20200401091803 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE IF EXISTS product ADD type VARCHAR(128) NOT NULL');

        $this->addSql('UPDATE public.event_store SET payload = jsonb_set(payload, \'{type}\', \'"SIMPLE-PRODUCT"\', TRUE) WHERE event_id = (SELECT id FROM public.event_store_event WHERE event_class = \'Ergonode\Product\Domain\Event\ProductCreatedEvent\')');

        $this->addSql('UPDATE public.event_store_event SET payload = jsonb_set(payload, \'{type}\', \'"SIMPLE-PRODUCT"\', TRUE) WHERE event_id = (SELECT id FROM public.event_store_event WHERE event_class = \'Ergonode\Product\Domain\Event\ProductCreatedEvent\')');

        $this->addSql('UPDATE public.product SET type = \'SIMPLE-PRODUCT\'');
    }
}
