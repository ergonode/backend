<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20201125114500 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM product_workflow_status where product_id IN 
                           (SELECT product_id FROM product_workflow_status WHERE product_id NOT IN 
                           (SELECT id FROM PRODUCT))');
    }
}
