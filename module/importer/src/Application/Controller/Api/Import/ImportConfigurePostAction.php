<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Import;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Attribute\Application\Form\AttributeCreateForm;
use Ergonode\Attribute\Application\Form\Model\CreateAttributeFormModel;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Application\Form\ConfigurationForm;
use Ergonode\Importer\Application\Model\Form\ConfigurationModel;
use Ergonode\Importer\Domain\Command\GenerateImportCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Importer\Infrastructure\Provider\SourceServiceProvider;

/**
 * @Route(
 *     name="ergonode_import_configure",
 *     path="/imports/{import}/configuration",
 *     methods={"POST"},
 *     requirements={"import" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ImportConfigurePostAction
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface $formFactory
     * @param CommandBusInterface  $commandBus
     */
    public function __construct(FormFactoryInterface $formFactory, CommandBusInterface $commandBus)
    {
        $this->formFactory = $formFactory;
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
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\AbstractImport")
     *
     * @param AbstractImport $import
     * @param Request        $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractImport $import, Request $request): Response
    {
        try {
            $model = new ConfigurationModel();
            $form = $this->formFactory->create(ConfigurationForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ConfigurationModel $data */
                $data = $form->getData();

                $command = new GenerateImportCommand(
                    $import->getId(),
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
