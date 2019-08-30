<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Importer\Domain\Entity\ImportId;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 */
class ImportIdHandler implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json', 'xml', 'yml'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => ImportId::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => ImportId::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param ImportId                      $id
     * @param array                         $type
     * @param Context                       $context
     *
     * @return string
     */
    public function serialize(SerializationVisitorInterface $visitor, ImportId $id, array $type, Context $context): string
    {
        return $id->getValue();
    }

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param string                          $data
     * @param array                           $type
     * @param Context                         $context
     *
     * @return ImportId
     */
    public function deserialize(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): ImportId
    {
        return new ImportId($data);
    }
}
