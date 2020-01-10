<?php


namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Email;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Exception;
use InvalidArgumentException;

/**
 */
class UserContext implements Context
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
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
        $userId = UserId::fromEmail(new Email($userEmail));
        $user = $this->userRepository->load($userId);
        if (!$user instanceof User) {
            throw new InvalidArgumentException(sprintf('There is no user with email %s', $userEmail));
        }

        return $user;
    }
}
