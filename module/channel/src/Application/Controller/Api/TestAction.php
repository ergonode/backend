<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\ExporterFile\Infrastructure\Processor\ProductProcessor;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 * @Route(
 *     name="ergonode_channel_test",
 *     path="/channels/{channel}/product/{product}",
 *     methods={"GET"},
 *     requirements={
 *          "channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *          "product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class TestAction
{
    private ProductProcessor $processor;

    public function __construct(ProductProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="channel",
     *     in="path",
     *     type="string",
     *     description="Channel id",
     * )
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product id",
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
     *     description="Returns export profile dictionary",
     * )
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     */
    public function __invoke(Language $language, AbstractChannel $channel, AbstractProduct $product): Response
    {
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);
        $data = $this->processor->process($channel, $product);


        return new SuccessResponse($data);
    }
}
