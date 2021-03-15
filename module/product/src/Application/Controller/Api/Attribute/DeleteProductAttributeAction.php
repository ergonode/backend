<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Attribute;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Domain\Command\Attribute\RemoveProductAttributeCommand;
use Ergonode\Api\Application\Response\EmptyResponse;

class DeleteProductAttributeAction
{
    private CommandBusInterface $commandBus;

    private LanguageQueryInterface $query;

    public function __construct(CommandBusInterface $commandBus, LanguageQueryInterface $query)
    {
        $this->commandBus = $commandBus;
        $this->query = $query;
    }

    /**
     * @Route(
     *     name="ergonode_product_attribute_delete",
     *     path="products/{product}/attribute/{attribute}",
     *     methods={"DELETE"},
     *     requirements={
     *         "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
     *         "attribute"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
     *     }
     * )
     *
     * @IsGranted("PRODUCT_ATTRIBUTE_DELETE")
     * @IsGranted("edit", subject="language")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product id",
     * )
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Change product attribute Value",
     * )
     *
     * @throws \Exception
     */
    public function removeDraftAttribute(
        AbstractProduct $product,
        Language $language,
        AbstractAttribute $attribute
    ): Response {
        if ($attribute->getScope()->isGlobal()) {
            $root = $this->query->getRootLanguage();
            if (!$root->isEqual($language)) {
                throw new AccessDeniedHttpException();
            }
        }
        $command = new RemoveProductAttributeCommand($product->getId(), $attribute->getId(), $language);
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
