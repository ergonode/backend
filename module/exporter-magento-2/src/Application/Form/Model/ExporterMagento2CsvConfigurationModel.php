<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Application\Form\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2CsvChannel;

class ExporterMagento2CsvConfigurationModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $filename = null;

    /**
     * @Assert\NotBlank()
     */
    public ?Language $defaultLanguage = null;

    public function __construct(Magento2CsvChannel $channel = null)
    {
        if ($channel) {
            $this->name = $channel->getName();
            $this->filename = $channel->getFilename();
            $this->defaultLanguage = $channel->getDefaultLanguage();
        }
    }
}
