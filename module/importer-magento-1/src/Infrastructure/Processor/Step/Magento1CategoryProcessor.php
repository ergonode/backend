<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Importer\Domain\Command\Import\ImportCategoryCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ramsey\Uuid\Uuid;

/**
 */
class Magento1CategoryProcessor implements Magento1ProcessorStepInterface
{
    private const UUID = '5bfd053c-e39b-45f9-87a7-6ca1cc9d9830';

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var string[]
     */
    private array $categories;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->categories = [];
    }

    /**
     * @param Import            $import
     * @param ProductModel      $product
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     */
    public function process(
        Import $import,
        ProductModel $product,
        Transformer $transformer,
        Magento1CsvSource $source
    ): void {
        $default = $product->get('default');
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
                        $command = new ImportCategoryCommand(
                            $import->getId(),
                            new CategoryCode($categoryCode),
                            $name
                        );

                        $this->commandBus->dispatch($command, true);
                        $this->categories[$categoryCode] = $categoryCode;
                    }

                    $default['esa_categories'] = implode(',', $codes);
                    $product->set('default', $default);
                }
            }
        }
    }
}
