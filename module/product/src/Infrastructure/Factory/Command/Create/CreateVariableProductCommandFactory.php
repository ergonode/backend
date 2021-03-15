<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Factory\Command\Create;

use Ergonode\Product\Domain\Command\Create\CreateProductCommandInterface;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Symfony\Component\Form\FormInterface;
use Ergonode\Product\Application\Model\Product\VariableProductFormModel;
use Ergonode\Product\Domain\Command\Create\CreateVariableProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Infrastructure\Factory\Command\CreateProductCommandFactoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class CreateVariableProductCommandFactory implements CreateProductCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === VariableProduct::TYPE;
    }
    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateProductCommandInterface
    {
        /** @var VariableProductFormModel $data */
        $data = $form->getData();
        $categories = [];
        foreach ($data->categories as $category) {
            $categories[] = new CategoryId($category);
        }

        return new CreateVariableProductCommand(
            ProductId::generate(),
            new Sku($data->sku),
            new TemplateId($data->template),
            $categories,
        );
    }
}
