<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api;

use Swagger\Annotations as SWG;
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
    private ProductTypeProvider $provider;

    private TranslatorInterface $translator;

    public function __construct(ProductTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
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
