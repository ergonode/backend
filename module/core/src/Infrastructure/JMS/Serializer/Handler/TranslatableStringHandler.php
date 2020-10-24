<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class TranslatableStringHandler implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => TranslatableString::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => TranslatableString::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param array $type
     *
     * @return array
     */
    public function serialize(
        SerializationVisitorInterface $visitor,
        TranslatableString $translatableString,
        array $type,
        Context $context
    ): array {
        return $visitor->visitArray(
            $translatableString->getTranslations(),
            $type
        );
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserialize(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type,
        Context $context
    ): TranslatableString {
        return new TranslatableString($data);
    }
}
