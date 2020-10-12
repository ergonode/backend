<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\Model;

use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
final class ImporterErgonodeConfigurationModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @var array
     */
    public array $import = [];

    /**
     * @param ErgonodeZipSource|null $source
     */
    public function __construct(?ErgonodeZipSource $source = null)
    {
        if ($source) {
            $this->name = $source->getName();

            foreach (ErgonodeZipSource::STEPS as $step) {
                if ($source->import($step)) {
                    $this->import[] = $step;
                }
            }
        }
    }
}
