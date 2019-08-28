<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Transformer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\Discriminator(
 *     field = "type",
 *     map = {
 *         "text": "Ergonode\Transformer\Infrastructure\Converter\TextConverter",
 *         "translation": "Ergonode\Transformer\Infrastructure\Converter\TranslationConverter",
 *         "slug": "Ergonode\Transformer\Infrastructure\Converter\SlugConverter",
 *         "join": "Ergonode\Transformer\Infrastructure\Converter\JoinConverter",
 *         "date": "Ergonode\Transformer\Infrastructure\Converter\DateConverter",
 *         "const": "Ergonode\Transformer\Infrastructure\Converter\ConstConverter",
 *         "mapping": "Ergonode\Transformer\Infrastructure\Converter\MappingConverter",
 *         "collection": "Ergonode\Transformer\Infrastructure\Converter\CollectionConverter",
 *         "dictionary": "Ergonode\Transformer\Infrastructure\Converter\DictionaryConverter",
 *         "split": "Ergonode\Transformer\Infrastructure\Converter\SplitConverter",
 *     }
 * )
 */
abstract class AbstractConverter
{
}
