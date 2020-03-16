<?php


namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Exception;
use InvalidArgumentException;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
class UserContext implements Context
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * @var UserQueryInterface
     */
    private UserQueryInterface $query;

    /**
     * @param UserRepositoryInterface $repository
     * @param UserQueryInterface      $query
     */
    public function __construct(UserRepositoryInterface $repository, UserQueryInterface $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @param string $userEmail
     *
     * @return User|AbstractAggregateRoot
     *
     * @throws Exception
     *
     * @Transform :user
     */
    public function castUserEmailToUser(string $userEmail): User
    {
        $userId = $this->query->findIdByEmail(new Email($userEmail));
        if (!$userId instanceof UserId) {
            throw new InvalidArgumentException(sprintf('There is no user with email %s', $userEmail));
        }
        $user = $this->repository->load($userId);
        if (!$user instanceof User) {
            throw new InvalidArgumentException(sprintf('There is no user with email %s', $userEmail));
        }

        return $user;
    }
}
