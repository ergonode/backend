<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
    private CompletenessCalculator  $calculator;

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
        $product = $this->productRepository->load($command->getProductId());
        if ($product) {
            $template = $this->templateRepository->load($product->getTemplateId());
            if ($template) {
                $this->manager->delete($product->getId());
                $languages = $this->query->getActive();
                foreach ($languages as $language) {
                    $result = $this->calculator->calculate($product, $template, $language);
                    foreach ($result as $element) {
                        $this->manager->add(
                            $product->getId(),
                            $template->getId(),
                            $element->getAttributeId(),
                            $language,
                            $element->isRequired(),
                            $element->isFilled()
                        );
                    }
                }
            }
        }
    }
}
