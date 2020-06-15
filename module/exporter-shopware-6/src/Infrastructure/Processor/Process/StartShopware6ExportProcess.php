<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Synchronize\CurrencySynchronize;
use Ergonode\ExporterShopware6\Infrastructure\Synchronize\TaxSynchronize;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class StartShopware6ExportProcess
{
    /**
     * @var TaxSynchronize
     */
    private TaxSynchronize $taxSynchronize;

    /**
     * @var CurrencySynchronize
     */
    private CurrencySynchronize $currencySynchronize;

    /**
     * @param TaxSynchronize      $taxSynchronize
     * @param CurrencySynchronize $currencySynchronize
     */
    public function __construct(TaxSynchronize $taxSynchronize, CurrencySynchronize $currencySynchronize)
    {
        $this->taxSynchronize = $taxSynchronize;
        $this->currencySynchronize = $currencySynchronize;
    }

    /**
     * @param ExportId                  $id
     * @param Shopware6ExportApiProfile $profile
     */
    public function process(ExportId $id, Shopware6ExportApiProfile $profile): void
    {
        $this->taxSynchronize->synchronize($id, $profile);
        $this->currencySynchronize->synchronize($id, $profile);
    }
}
