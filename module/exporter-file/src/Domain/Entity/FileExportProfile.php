<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Domain\Entity;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Entity\Profile\ExportProfileInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class FileExportProfile extends AbstractExportProfile implements ExportProfileInterface
{
    public const TYPE = 'file';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $format;

    /**
     * @param ExportProfileId $id
     * @param string          $name
     * @param string          $format
     */
    public function __construct(ExportProfileId $id, string $name, string $format)
    {
        parent::__construct($id, $name);

        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }
}
