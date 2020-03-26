<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Application\Form\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ExporterMagento2CsvConfigurationModel
{
    /**
     * @var string|null
     *
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @var string|null
     *
     * @Assert\Length(min=2)
     */
    public ?string $filename = null;

    /**
     * @var Language|null
     *
     * @Assert\NotBlank()
     */
    public ?Language $defaultLanguage = null;

    /**
     * @param Magento2ExportCsvProfile|null $exportProfile
     */
    public function __construct(Magento2ExportCsvProfile $exportProfile = null)
    {
        if ($exportProfile) {
            $this->name = $exportProfile->getName();
            $this->filename = $exportProfile->getFilename();
            $this->defaultLanguage = $exportProfile->getDefaultLanguage();
        }
    }
}
