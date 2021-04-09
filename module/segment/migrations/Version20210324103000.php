<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210324103000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE segment_product');
        $this->addSql('INSERT INTO segment_product (segment_id, product_id) 
                           SELECT s.id, p.id FROM segment AS s JOIN product AS p ON 1=1');
    }
}
