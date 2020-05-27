<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Factory\Command\Update;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Symfony\Component\Form\FormInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Application\Model\Product\SimpleProductFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Domain\Command\Update\UpdateSimpleProductCommand;
use Ergonode\Product\Infrastructure\Factory\Command\UpdateProductCommandFactoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
class UpdateSimpleProductCommandFactory implements UpdateProductCommandFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return $type === SimpleProduct::TYPE;
    }

    /**
     * @param ProductId     $productId
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function create(ProductId $productId, FormInterface $form): DomainCommandInterface
    {
        /** @var SimpleProductFormModel $data */
        $data = $form->getData();

        $categories = [];
        foreach ($data->categories as $category) {
            $categories[] = new CategoryId($category);
        }

        return new UpdateSimpleProductCommand(
            $productId,
            new TemplateId($data->template),
            $categories,
        );
    }
}
