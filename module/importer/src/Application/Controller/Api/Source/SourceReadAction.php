<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Importer\Application\Provider\SourceFormFactoryProvider;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
    private SourceFormFactoryProvider $provider;

    private NormalizerInterface $normalizer;

    public function __construct(SourceFormFactoryProvider $provider, NormalizerInterface $normalizer)
    {
        $this->provider = $provider;
        $this->normalizer = $normalizer;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_IMPORT_GET_SOURCE")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
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
     */
    public function __invoke(AbstractSource $source): array
    {
        $form = $this->provider->provide($source->getType())->create($source);
        $result = $this->normalizer->normalize($form);
        $result['type'] = $source->getType();

        return $result;
    }
}
