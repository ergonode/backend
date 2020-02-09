<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class Magento1CategoryProcessor
{
    /**
     * @param string[] $rows
     * @param Language $language
     *
     * @return Record[]
     */
    public function process(array $rows, Language $language): array
    {
        $defaultLanguage = $language->getCode();
        $result = [];
        foreach ($rows as $row) {
            if ($row['sku'] && $row['_category']) {
                $categories = explode('/', $row['_category']);
                foreach ($categories as $category) {
                    $code = $category;
                    if(!array_key_exists($code, $result)) {
                        $language = !empty($row['_store']) ? $row['_store'] : $defaultLanguage;
                        $name = [$language => $category];
                        $record = new Record();
                        $record->set('code', new StringValue($code));
                        $record->set('name', new TranslatableStringValue(new TranslatableString($name)));
                        $result[$code] = $record;
                    }
                }
            }
        }

        return $result;
    }
}