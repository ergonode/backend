<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api;

use Ergonode\Account\Application\Form\Model\RoleFormModel;
use Ergonode\Account\Application\Form\RoleForm;
use Ergonode\Account\Domain\Command\Role\CreateRoleCommand;
use Ergonode\Account\Domain\Command\Role\DeleteRoleCommand;
use Ergonode\Account\Domain\Command\Role\UpdateRoleCommand;
use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Infrastructure\Grid\RoleGrid;
use Ergonode\Account\Persistence\Manager\RoleAggregateRootManager;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class RoleController extends AbstractController
{
    /**
     * @var RoleQueryInterface
     */
    private $query;

    /**
     * @var RoleGrid
     */
    private $grid;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var RoleAggregateRootManager
     */
    private $roleAggregateRootManager;

    /**
     * @param RoleQueryInterface  $query
     * @param RoleGrid            $grid
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        RoleQueryInterface $query,
        RoleGrid $grid,
        MessageBusInterface $messageBus,
        RoleRepositoryInterface $roleRepository,
        RoleAggregateRootManager $roleAggregateRootManager
    ) {
        $this->query = $query;
        $this->grid = $grid;
        $this->messageBus = $messageBus;
        $this->roleRepository = $roleRepository;
        $this->roleAggregateRootManager = $roleAggregateRootManager;
    }

    /**
     * @Route("/test", methods={"GET"})
     *
     * @SWG\Tag(name="Account")
     * @SWG\Response(
     *     response=200,
     *     description="Returns roles collection",
     * )
     */
    public function test(): Response
    {
        $roleId = new RoleId('ac6ad933-780a-469d-9e5c-b35d74067f2c');

        $time = microtime(true);

        $object = $this->roleRepository->load($roleId);
        var_dump($object);

        $result['repository'] = microtime(true) - $time;

        $time = microtime(true);

        $object = $this->roleAggregateRootManager->load($roleId);
        var_dump($object);

        $result['manager'] = microtime(true) - $time;

        /**
         * // with 5k events (without cache)
         * ["repository"]=> float(0.47474408149719)
         * ["manager"]   => float(0.40530800819397) -0.07s ~20%
         * // with 5k events (with cache)
         * ["repository"]=> float(0.15640807151794)
         * ["manager"]   => float(0.0021481513977051) -0.154s ~740%
         * // with 50k events (without cache)
         * ["repository"]=> float(4.995169878006)
         * ["manager"]   => float(4.0791778564453) -0.92s ~20%
         * // with 50k events (with cache)
         * ["repository"]=> float(1.9104011058807)
         * ["manager"]   => float(0.0020561218261719) -1.908s >1000%
         * // add 1k events (with cache)
         * ["repository"]=> float(1.9918200969696
         * ["manager"]   => float(0.077238082885742) -1.22s >1000%
         * // add 1k events again (with cache)
         * ["repository"]=> float(2.018196105957)
         * ["manager"]   => float(0.074703931808472) -1.96s >1000%
         *
         * Killers:
         * 1/ SimpleDomainEventFactory! Almost 7,5s in 100k records! Thanks for JMS!
         * 2/ DbalRoleRepository::load, it's steal 1,3s for 100k event propagation!
         *
         * Summary:
         * 1/ manager solution will be much much faster when we use the same object again
         * 2/ manager is little bit faster when we build object without cache
         */

        var_dump($result);

        return new EmptyResponse();
    }

    /**
     * @Route("/roles", methods={"GET"})
     *
     * @IsGranted("USER_ROLE_READ")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"id","name","description", "users_count"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns roles collection",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getRoles(Language $language, RequestGridConfiguration $configuration): Response
    {
        return new GridResponse($this->grid, $configuration, $this->query->getDataSet(), $language);
    }

    /**
     * @Route("/roles/{role}", methods={"GET"}, requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_ROLE_READ")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role Id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns role data",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\Role")
     *
     * @param Role $role
     *
     * @return Response
     */
    public function getRole(Role $role): Response
    {
        return new SuccessResponse($role);
    }

    /**
     * @Route("/roles", methods={"POST"})
     *
     * @IsGranted("USER_ROLE_CREATE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/role")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns role data",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createRole(Request $request): Response
    {
        try {
            $model = new RoleFormModel();
            $form = $this->createForm(RoleForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var RoleFormModel $data */
                $data = $form->getData();
                $command = new CreateRoleCommand($data->name, $data->description, $data->privileges);
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/roles/{role}", methods={"PUT"}, requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_ROLE_UPDATE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role Id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language code",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/role")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\Role")
     *
     * @param Role    $role
     * @param Request $request
     *
     * @return Response
     */
    public function updateRole(Role $role, Request $request): Response
    {
        try {
            $model = new RoleFormModel();
            $form = $this->createForm(RoleForm::class, $model, ['method' => Request::METHOD_PUT]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var RoleFormModel $data */
                $data = $form->getData();

                $command = new UpdateRoleCommand(
                    $role->getId(),
                    $data->name,
                    $data->description,
                    $data->privileges
                );
                $this->messageBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/roles/{role}", methods={"DELETE"}, requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_ROLE_DELETE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role Id",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Can't delete Role",
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\Role")
     *
     * @param Role $role
     *
     * @return Response
     */
    public function deleteRole(Role $role): Response
    {
        $roleUsersCount = $this->query->getRoleUsersCount($role->getId());
        if (0 === $roleUsersCount) {
            $command = new DeleteRoleCommand($role->getId());
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new ConflictHttpException(sprintf(
            'Can\'t delete role "%s", users are assigned to it',
            $role->getId()->getValue()
        ));
    }
}
