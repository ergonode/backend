<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ramsey\Uuid\UuidInterface;

/**
 */
final class Version20201112075040 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS language_tree (
                id UUID NOT NULL,
                parent_id UUID DEFAULT NULL,
                lft INT NOT NULL,
                rgt INT NOT NULL,
                code VARCHAR(5) NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $parent = $this->add(1, 6, new Language('en'));
        $parent = $this->add(2, 5, new Language('pl'), $parent);
        $this->add(3, 4, new Language('fr'), $parent);
    }

    /**
     * @param int                $lft
     * @param int                $rgt
     * @param Language           $language
     * @param UuidInterface|null $parent
     *
     * @return Uuid
     *
     * @throws \Exception
     */
    private function add(
        int $lft,
        int $rgt,
        Language $language,
        ?UuidInterface $parent = null
    ): UuidInterface {
        $id = Uuid::uuid4();
        $this->addSql(
            'INSERT INTO language_tree (id, parent_id, lft, rgt, code) VALUES(?,?,?,?,?)',
            [
                $id->toString(),
                $parent ? $parent->toString() : null,
                $lft,
                $rgt,
                $language->getCode(),
            ]
        );

        return $id;
    }
}
