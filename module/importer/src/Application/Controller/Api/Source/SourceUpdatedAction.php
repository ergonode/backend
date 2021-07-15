<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Application\Provider\SourceFormFactoryProvider;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Importer\Application\Provider\UpdateSourceCommandBuilderProvider;

/**
 * @Route(
 *     name="ergonode_source_update",
 *     path="/sources/{source}",
 *     methods={"PUT"},
 *     requirements={"source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SourceUpdatedAction
{
    private SourceFormFactoryProvider $provider;

    private UpdateSourceCommandBuilderProvider $commandProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        SourceFormFactoryProvider $provider,
        UpdateSourceCommandBuilderProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->provider = $provider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_IMPORT_PUT_SOURCE")
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
     * @SWG\Parameter(
     *     name="import",
     *     in="path",
     *     type="string",
     *     description="Import id",
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
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Source\AbstractSource", name="source")
     *
     * @throws \Exception
     */
    public function __invoke(AbstractSource $source, Request $request): SourceId
    {
        try {
            $form = $this->provider->provide($source->getType())->create($source);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->commandProvider->provide($source->getType())->build($source->getId(), $form);
                $this->commandBus->dispatch($command);

                return $command->getId();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
