<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Magento2ExportCsvProfile extends AbstractExportProfile
{
    public const TYPE = 'magento-2-csv';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $filename;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @param ExportProfileId $id
     * @param string          $name
     * @param string          $filename
     * @param Language        $defaultLanguage
     */
    public function __construct(ExportProfileId $id, string $name, string $filename, Language $defaultLanguage)
    {
        parent::__construct($id, $name);
        $this->filename = $filename;
        $this->defaultLanguage = $defaultLanguage;
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
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }
}
