<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\JMS\Serializer\Handler;

use Ergonode\BatchAction\Application\Form\Model\BatchActionFilterIdsFormModel;
use JMS\Serializer\Context;
use JMS\Serializer\Exception\InvalidArgumentException;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class BatchActionFilterIdsFormModelHandler implements SubscribingHandlerInterface
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
                'type' => BatchActionFilterIdsFormModel::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => BatchActionFilterIdsFormModel::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param array $type
     */
    public function serialize(
        SerializationVisitorInterface $visitor,
        BatchActionFilterIdsFormModel $batchActionFilterIdsFormModel,
        array $type,
        Context $context
    ): array {
        return [
            "list" => $batchActionFilterIdsFormModel->list,
            "include" => $batchActionFilterIdsFormModel->included,
        ];
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
    ): BatchActionFilterIdsFormModel {
        $model = new BatchActionFilterIdsFormModel();
        if (is_array($data['list'] ?? null)) {
            $model->list = $data['list'];
        }
        if (!isset($data['included'])) {
            return $model;
        }
        if (is_bool($data['included'])) {
            $model->included = $data['included'];
        } else {
            switch ($data['included']) {
                case 'true':
                    $included = true;
                    break;
                case 'false':
                    $included = false;
                    break;
                default:
                    throw new InvalidArgumentException('Only string and bool data supported');
            }
            $model->included = $included;
        }

        return $model;
    }
}
