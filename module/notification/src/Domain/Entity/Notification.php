<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Domain\Entity;

use Ergonode\Account\Domain\Entity\UserId;

/**
 */
class Notification
{
    /**
     * @var NotificationId
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $message;

    /**
     * @var UserId|null
     */
    private $authorId;

    /**
     * @param NotificationId $id
     * @param \DateTime      $createdAt
     * @param string         $message
     * @param UserId|null    $authorId
     */
    public function __construct(NotificationId $id, \DateTime $createdAt, string $message, ?UserId $authorId = null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->message = $message;
        $this->authorId = $authorId;
    }

    /**
     * @return NotificationId
     */
    public function getId(): NotificationId
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return UserId|null
     */
    public function getAuthorId(): ?UserId
    {
        return $this->authorId;
    }
}
