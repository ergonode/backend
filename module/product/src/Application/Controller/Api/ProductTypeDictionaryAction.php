<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Product\Application\Provider\ProductTypeProvider;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Api\Application\Response\SuccessResponse;

/**
 * @Route(
 *     name="ergonode_product_type_dictionary",
 *     path="dictionary/product-type",
 *     methods={"GET"}
 * )
 */
class ProductTypeDictionaryAction
{
    /**
     * @var ProductTypeProvider
     */
    private ProductTypeProvider $provider;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param ProductTypeProvider $provider
     * @param TranslatorInterface $translator
     */
    public function __construct(ProductTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @IsGranted("PRODUCT_UPDATE")
     *
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection supported product types"
     * )
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(): Response
    {
        $dictionary = [];
        $types = $this->provider->provide();
        foreach ($types as $type) {
            $dictionary[$type] = $this->translator->trans($type, [], 'product');
        }

        return new SuccessResponse($dictionary);
    }
}
