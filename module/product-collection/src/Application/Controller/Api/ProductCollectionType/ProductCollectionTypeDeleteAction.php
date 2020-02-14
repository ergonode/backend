<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollectionType;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_type_delete",
 *     path="/collections/types/{type}",
 *     methods={"DELETE"},
 *     requirements={"type"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductCollectionTypeDeleteAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("PRODUCT_COLLECTION_DELETE")
     *
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Product Collection type ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\ProductCollection\Domain\Entity\ProductCollectionType")
     *
     * @param ProductCollectionType $productCollection
     * @param Request               $request
     *
     * @return Response
     */
    public function __invoke(ProductCollectionType $productCollection, Request $request): Response
    {
        $command = new DeleteProductCollectionTypeCommand($productCollection->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
