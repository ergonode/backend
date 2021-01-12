<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Category\Domain\Provider\Dictionary\CategoryTypeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/categories/types", methods={"GET"})
 */
class CategoryTypeReadAction
{
    private CategoryTypeDictionaryProvider $categoryTypeDictionaryProvider;

    public function __construct(CategoryTypeDictionaryProvider $categoryTypeDictionaryProvider)
    {
        $this->categoryTypeDictionaryProvider = $categoryTypeDictionaryProvider;
    }

    /**
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection category types"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Language $language): Response
    {
        $types = $this->categoryTypeDictionaryProvider->getDictionary($language);

        return new SuccessResponse($types);
    }
}
