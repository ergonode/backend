<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;

/**
 */
final class OptionProcessor
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param FileExportChannel $channel
     * @param AbstractOption    $option
     *
     * @return ExportData
     *
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractOption $option): ExportData
    {
        try {
            $attribute = $this->attributeRepository->load($option->getAttributeId());
            if (null === $attribute) {
                throw new \InvalidArgumentException('Attribute not found');
            }

            $data = new ExportData();
            foreach ($channel->getLanguages() as $language) {
                $data->set($this->getLanguage($option, $language, $attribute), $language);
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $option->getCode()->getValue()),
                $exception
            );
        }
    }

    /**
     * @param AbstractOption    $option
     * @param Language          $language
     * @param AbstractAttribute $attribute
     *
     * @return LanguageData
     */
    private function getLanguage(AbstractOption $option, Language $language, AbstractAttribute $attribute): LanguageData
    {
        $result = new LanguageData();
        $result->set('_id', $option->getId()->getValue());
        $result->set('_code', $option->getCode()->getValue());
        $result->set('_attribute_id', $option->getAttributeId()->getValue());
        $result->set('_attribute_code', $attribute->getCode()->getValue());
        $result->set('_language', $language->getCode());
        $result->set('_label', $option->getLabel()->get($language));

        return $result;
    }
}
