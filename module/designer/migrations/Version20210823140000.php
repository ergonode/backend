<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210823140000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->connection->executeQuery('ALTER TABLE designer.template ADD COLUMN code VARCHAR(128) DEFAULT NULL');
        $templates = $this
            ->connection
            ->executeQuery('SELECT id, name FROM designer.template WHERE code IS NULL')
            ->fetchAllAssociative();

        foreach ($templates as $template) {
            $this->setTemplateCode($template['id'], $template['name']);
        }

        $this
            ->connection
            ->executeQuery('ALTER TABLE designer.template ALTER COLUMN code SET NOT NULL');
        $this
            ->connection
            ->executeQuery('CREATE UNIQUE INDEX template_code_unique_key ON designer.template USING btree (code)');
    }

    private function setTemplateCode(string $id, string $name): void
    {
        $code = $name;
        $i = 0;
        while (!$this->changeCode($id, $code)) {
            $i++;
            $code = substr($name, 0, 120).'_'.$i;
        }
    }

    private function changeCode(string $id, string $code): bool
    {
        $template = $this
            ->connection
            ->executeQuery('SELECT id FROM designer.template WHERE code = ?', [$code])
            ->fetchOne();

        if (!$template) {
            $this
                ->connection
                ->executeQuery('UPDATE designer.template SET code = ? WHERE id = ?', [$code, $id]);

            return  true;
        }

        return false;
    }
}
