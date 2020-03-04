<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Importer\Application\Provider\SourceFormFactoryProvider;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     name="ergonode_source_read",
 *     path="/sources/{source}",
 *     methods={"GET"},
 *     requirements={"source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SourceReadAction
{
    /**
     * @var SourceFormFactoryProvider
     */
    private SourceFormFactoryProvider $provider;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SourceFormFactoryProvider $provider
     * @param SerializerInterface       $serializer
     */
    public function __construct(SourceFormFactoryProvider $provider, SerializerInterface $serializer)
    {
        $this->provider = $provider;
        $this->serializer = $serializer;
    }

    /**
     * @IsGranted("IMPORT_READ")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns import ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Source\AbstractSource")
     *
     * @param AbstractSource $source
     *
     * @return Response
     *
     */
    public function __invoke(AbstractSource $source): Response
    {
        $form = $this->provider->provide($source->getType())->create($source);

        return new SuccessResponse($this->serializer->normalize($form));
    }
}
