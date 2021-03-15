<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Product;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Webmozart\Assert\Assert;

class ExportProductTemplateBuilder implements ExportProductBuilderInterface
{
    private TemplateRepositoryInterface $templateRepository;

    public function __construct(TemplateRepositoryInterface $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function header(): array
    {
        return ['_template'];
    }

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void
    {
        $result->set('_template', $this->getTemplateName($product->getTemplateId()));
    }

    private function getTemplateName(TemplateId $templateId): string
    {
        $template = $this->templateRepository->load($templateId);
        Assert::notNull($template);

        return $template->getName();
    }
}
