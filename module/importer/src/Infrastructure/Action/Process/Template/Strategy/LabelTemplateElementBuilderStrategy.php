<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Template\Strategy;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\Importer\Infrastructure\Action\Process\Template\TemplateElementBuilderInterface;

class LabelTemplateElementBuilderStrategy implements TemplateElementBuilderInterface
{
    public function supported(string $type): bool
    {
        return UiTemplateElement::TYPE === $type;
    }

    public function build(
        Template $template,
        Position $position,
        Size $size,
        array $properties
    ): TemplateElementInterface {

        $label = $properties['label'];

        if (empty($label)) {
            throw new ImportException(
                'template {name} element label shouldn\'t be empty',
                [
                    '{name}' => $template->getName(),
                ]
            );
        }

        return new UiTemplateElement($position, $size, $label);
    }
}
