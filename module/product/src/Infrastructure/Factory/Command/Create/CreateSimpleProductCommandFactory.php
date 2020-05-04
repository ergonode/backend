<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Factory\Command\Create;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Symfony\Component\Form\FormInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Application\Model\Product\SimpleProductFormModel;
use Ergonode\Product\Domain\Command\Create\CreateSimpleProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Infrastructure\Factory\Command\CreateProductCommandFactoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
class CreateSimpleProductCommandFactory implements CreateProductCommandFactoryInterface
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
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     *
     * @throws \Exception
     */
    public function create(FormInterface $form): DomainCommandInterface
    {
        /** @var SimpleProductFormModel $data */
        $data = $form->getData();
        $categories = [];
        foreach ($data->categories as $category) {
            $categories[] = new CategoryId($category);
        }

        return new CreateSimpleProductCommand(
            ProductId::generate(),
            new Sku($data->sku),
            new TemplateId($data->template),
            $categories,
        );
    }
}
