<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Application\Form\ConfigurationForm;
use Ergonode\Importer\Application\Model\Form\ConfigurationModel;
use Ergonode\Importer\Domain\Command\GenerateImportCommand;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\ImporterMagento1\Application\Factory\ImporterMagento1ConfigurationFormFactory;

/**
 * @Route(
 *     name="ergonode_source_configuration",
 *     path="/sources/{source}/configuration",
 *     methods={"POST"},
 *     requirements={"source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SourcePostConfigurationAction
{
    /**
     * @var ImporterMagento1ConfigurationFormFactory
     */
    private $configurationFormFactory;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ImporterMagento1ConfigurationFormFactory $configurationFormFactory
     * @param CommandBusInterface                      $commandBus
     */
    public function __construct(
        ImporterMagento1ConfigurationFormFactory $configurationFormFactory,
        CommandBusInterface $commandBus
    ) {
        $this->configurationFormFactory = $configurationFormFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("IMPORT_CREATE")
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
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Source\AbstractSource")
     *
     * @param AbstractSource $source
     * @param Request        $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractSource $source, Request $request): Response
    {
        try {
            $form = $this->configurationFormFactory->create($source);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ConfigurationModel $data */
                $data = $form->getData();

                $command = new GenerateImportCommand(
                    $source->getId(),
                    $data
                );
                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
