<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210119140233 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'DELETE FROM product_workflow_status 
                    USING (
                        SELECT product_id, "language"
                        FROM product_workflow_status
                        GROUP BY product_id, "language"
                        HAVING COUNT(status_id) > 1
                    ) a 
                    WHERE 
                        a.product_id = product_workflow_status.product_id
                        AND a."language" = product_workflow_status."language"'
        );

        $this->addSql(
            'INSERT INTO public.product_workflow_status (product_id, status_id, "language")
	                SELECT pv.product_id, vt.value::uuid , vt."language" 
	                FROM  value_translation vt
	                JOIN product_value pv on pv.value_id = vt.value_id
	                JOIN "attribute" a2 on a2.id = pv.attribute_id 
	                WHERE a2.code = \'esa_status\'
	                ON CONFLICT DO NOTHING'
        );

        $this->addSql(
            'ALTER TABLE product_workflow_status
                    DROP CONSTRAINT product_workflow_status_pkey'
        );

        $this->addSql(
            'ALTER TABLE product_workflow_status
                    ADD CONSTRAINT product_workflow_status_pkey PRIMARY KEY (product_id, "language")'
        );
    }
}
