<?php
declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use DateTime;

class Mail implements MailInterface
{
    /**
     * @var Recipient
     */
    protected Recipient $recipient;

    /**
     * @var Sender
     */
    protected Sender $sender;

    /**
     * @var Template
     */
    protected Template $template;

    /**
     * @var string
     */
    protected string $subject;

    /**
     * @var DateTime
     */
    protected DateTime $createdAt;

    /**
     * @param Recipient $recipient
     * @param Sender    $sender
     * @param Template  $template
     * @param string    $subject
     */
    public function __construct(
        Recipient $recipient,
        Sender $sender,
        Template $template,
        string $subject
    ) {
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->template = $template;
        $this->subject = $subject;
        $this->createdAt = new DateTime();
    }

    /**
     * {@inheritDoc}
     */
    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    /**
     * {@inheritDoc}
     */
    public function getSender(): Sender
    {
        return $this->sender;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
