<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository\Factory;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use JMS\Serializer\SerializerInterface;

/**
 */
class ExportProfileFactory
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param array $record
     *
     * @return AbstractExportProfile
     */
    public function create(array $record): AbstractExportProfile
    {
        $class = $record['class'];
        $data = $record['configuration'];

        return $this->serializer->deserialize($data, $class, 'json');
    }
}
