<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Relations;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Product\Domain\Command\Relations\AddProductChildrenBySegmentsCommand;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Product\Application\Form\Product\Relation\ProductChildBySegmentsForm;
use Ergonode\Product\Application\Model\Product\Relation\ProductChildBySegmentsFormModel;

/**
 * @Route(
 *     name="ergonode_product_child_add_from_segments",
 *     path="products/{product}/children/add-from-segments",
 *     methods={"POST"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductAddChildFromSegmentsAction extends AbstractController
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("PRODUCT_POST_RELATIONS_CHILDREN_SEGMENT")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product ID",
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
     *     description="Returns import",
     * )
     */
    public function __invoke(Language $language, AbstractProduct $product, Request $request): Response
    {
        try {
            $model = new ProductChildBySegmentsFormModel();
            $form = $this->formFactory->create(ProductChildBySegmentsForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductChildBySegmentsFormModel $data */
                $data = $form->getData();
                $segments = [];
                foreach ($data->segments as $segment) {
                    $segments[] = new SegmentId($segment);
                }
                $command = new AddProductChildrenBySegmentsCommand($product, $segments);
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
