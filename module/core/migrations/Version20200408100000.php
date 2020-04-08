<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
class Version20200408100000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {

        $this->createLanguagePrivileges([
            'EN_PRIVILEGE_EDIT' => 'en',
            'EN_PRIVILEGE_READ' => 'en',
            'EN_BS_PRIVILEGE_EDIT' => 'en_BS',
            'EN_BS_PRIVILEGE_READ' => 'en_BS',
            'PL_PRIVILEGE_EDIT' => 'pl',
            'PL_PRIVILEGE_READ' => 'pl',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createLanguagePrivileges(array $collection): void
    {
        foreach ($collection as $code => $language) {
            $this->connection->insert('language_privileges', [
                'id' => Uuid::uuid4()->toString(),
                'language' => $language,
                'code' => $code,
            ]);
        }
    }
}
