<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Importer\Domain\Command\Import\ImportCategoryCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ramsey\Uuid\Uuid;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class Magento1CategoryProcessor implements Magento1ProcessorStepInterface
{
    private const UUID = '5bfd053c-e39b-45f9-87a7-6ca1cc9d9830';

    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    /**
     * @var string[]
     */
    private array $categories;

    public function __construct(CommandBusInterface $commandBus, ImportRepositoryInterface $importRepository)
    {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
        $this->categories = [];
    }

    /**
     * @param AbstractAttribute[] $attributes
     */
    public function process(
        Import $import,
        ProductModel $product,
        Magento1CsvSource $source,
        array $attributes
    ): void {
        $default = $product->getDefault();
        if (array_key_exists('esa_categories', $default) && $default['esa_categories'] !== '') {
            $categories = explode(',', $default['esa_categories']);
            $codes = [];
            foreach ($categories as $category) {
                $category = explode('/', $category);
                $code = end($category);

                if ('' !== $code) {
                    $uuid = Uuid::uuid5(self::UUID, $code)->toString();
                    $categoryCode = sprintf('category-%s', $uuid);
                    $codes[] = $categoryCode;
                    $name = new TranslatableString([$source->getDefaultLanguage()->getCode() => end($category)]);

                    if (!array_key_exists($categoryCode, $this->categories)) {
                        $id = ImportLineId::generate();
                        $command = new ImportCategoryCommand(
                            $id,
                            $import->getId(),
                            $categoryCode,
                            $name
                        );
                        $this->importRepository->addLine($id, $import->getId(), 'CATEGORY');
                        $this->commandBus->dispatch($command, true);
                        $this->categories[$categoryCode] = $categoryCode;
                    }

                    $default['esa_categories'] = implode(',', $codes);
                    $product->setDefault($default);
                }
            }
        }
    }
}
