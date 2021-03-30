<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ramsey\Uuid\Uuid;
use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class Magento1OptionProcessor implements Magento1ProcessorStepInterface
{
    private const NAMESPACE = 'fee77612-b07d-4eea-af71-d4e1e6c3ea1a';

    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    /**
     * @var string[]
     */
    private array $options = [];

    public function __construct(
        CommandBusInterface $commandBus,
        ImportRepositoryInterface $importRepository
    ) {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
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
        $columns = [];

        foreach ($product->getDefault() as $key => $item) {
            if ('_' !== $key[0] && false === strpos($key, 'esa_')) {
                $columns[$key][] = $item;
            }
        }
        foreach (array_keys($source->getLanguages()) as $store) {
            if ($product->has($store)) {
                foreach ($product->get($store) as $key => $item) {
                    if ('_' !== $key[0] && false === strpos($key, 'esa_')) {
                        $columns[$key][] = $item;
                    }
                }
            }
        }

        foreach ($columns as $key => $array) {
            $columns[$key] = array_unique($array);
        }

        foreach ($attributes as $attribute) {
            $type = $attribute->getType();
            if (SelectAttribute::TYPE === $type || MultiSelectAttribute::TYPE === $type) {
                $attributeCode = $attribute->getCode();
                if (!array_key_exists($attributeCode->getValue(), $columns)) {
                    $columns[$attributeCode->getValue()] = [];
                }
                $options = $this->getOptions($columns[$attributeCode->getValue()]);
                foreach ($options as $key => $option) {
                    $uuid = Uuid::uuid5(self::NAMESPACE, sprintf('%s/%s', $attributeCode->getValue(), $key));
                    if (!array_key_exists($uuid->toString(), $this->options)) {
                        $id = ImportLineId::generate();
                        $label = new TranslatableString();
                        $label = $label->add($source->getDefaultLanguage(), $option);
                        $command = new ImportOptionCommand(
                            $id,
                            $import->getId(),
                            $attribute->getCode()->getValue(),
                            (string) $key,
                            $label
                        );
                        $this->importRepository->addLine($id, $import->getId(), 'OPTION');
                        $this->commandBus->dispatch($command, true);
                        $this->options[$uuid->toString()] = $uuid;
                    }
                }
            }
        }
    }

    /**
     * @param string[] $column
     *
     * @return string[]
     */
    private function getOptions(array $column): array
    {
        $result = [];
        $unique = array_unique($column);
        foreach ($unique as $element) {
            if ('' !== $element && null !== $element) {
                $result[$element] = $element;
            }
        }

        return $result;
    }
}
