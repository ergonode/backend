<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Handler\Command;

use Ergonode\Completeness\Domain\Command\ProductCompletenessCalculateCommand;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Doctrine\DBAL\DBALException;
use Ergonode\Completeness\Infrastructure\Persistence\Manager\CompletenessManager;

class ProductCompletenessCalculateCommandHandler
{
    private CompletenessCalculator $calculator;

    private LanguageQueryInterface $query;

    private ProductRepositoryInterface $productRepository;

    private TemplateRepositoryInterface $templateRepository;

    private CompletenessManager $manager;

    public function __construct(
        CompletenessCalculator $calculator,
        LanguageQueryInterface $query,
        ProductRepositoryInterface $productRepository,
        TemplateRepositoryInterface $templateRepository,
        CompletenessManager $manager
    ) {
        $this->calculator = $calculator;
        $this->query = $query;
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
        $this->manager = $manager;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(ProductCompletenessCalculateCommand $command): void
    {
        $productId = $command->getProductId();
        $product = $this->productRepository->load($productId);

        if ($product) {
            $template = $this->templateRepository->load($product->getTemplateId());
            if ($template) {
                $languages = $this->query->getActive();
                $required = [];
                $filled = [];
                $completeness = [];
                foreach ($languages as $language) {
                    $completeness[$language->getCode()] = 100;
                    $required[$language->getCode()] = 0;
                    $filled[$language->getCode()] = 0;
                    foreach ($this->calculator->calculate($product, $template, $language) as $entry) {
                        $required[$language->getCode()] += (int) $entry->isRequired();
                        $filled[$language->getCode()] += (int) $entry->isFilled();
                    }
                    if ($required[$language->getCode()]) {
                        $completeness[$language->getCode()] =
                            $filled[$language->getCode()] / $required[$language->getCode()] * 100;
                    }
                }

                $this->manager->updateCompleteness($productId, $completeness);
            }
        }
    }
}
