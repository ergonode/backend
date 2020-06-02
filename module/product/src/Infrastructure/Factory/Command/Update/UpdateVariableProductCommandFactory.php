<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Factory\Command\Update;

use Ergonode\Product\Domain\Entity\VariableProduct;
use Symfony\Component\Form\FormInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Application\Model\Product\VariableProductFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Domain\Command\Update\UpdateVariableProductCommand;
use Ergonode\Product\Infrastructure\Factory\Command\UpdateProductCommandFactoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
class UpdateVariableProductCommandFactory implements UpdateProductCommandFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return $type === VariableProduct::TYPE;
    }

    /**
     * @param ProductId     $productId
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function create(ProductId $productId, FormInterface $form): DomainCommandInterface
    {
        /** @var VariableProductFormModel $data */
        $data = $form->getData();

        $categories = [];
        foreach ($data->categories as $category) {
            $categories[] = new CategoryId($category);
        }

        $bindings = [];
        foreach ($data->bindings as $binding) {
            $bindings[] = new AttributeId($binding);
        }

        return new UpdateVariableProductCommand(
            $productId,
            new TemplateId($data->template),
            $categories,
            $bindings,
        );
    }
}
