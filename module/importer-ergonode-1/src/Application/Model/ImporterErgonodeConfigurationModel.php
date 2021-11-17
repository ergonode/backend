<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Application\Model;

use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Symfony\Component\Validator\Constraints as Assert;

class ImporterErgonodeConfigurationModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;
    public array $import = [];

    /**
     * @var DownloadHeaderModel[]
     *
     * @Assert\Valid()
     */
    public array $headers = [];

    public function __construct(?ErgonodeZipSource $source = null)
    {
        if ($source) {
            $this->name = $source->getName();

            foreach ($source->getSteps() as $step) {
                if ($source->import($step)) {
                    $this->import[] = $step;
                }
            }

            foreach ($source->getHeaders() as $value) {
                $this->headers[] = new DownloadHeaderModel($value->getKey(), $value->getValue());
            }
        }
    }
}
