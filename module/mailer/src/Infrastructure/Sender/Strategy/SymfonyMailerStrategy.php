<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Sender\Strategy;

use Ergonode\Mailer\Domain\MailMessageInterface;
use Ergonode\Mailer\Infrastructure\Sender\MailerStrategyInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class SymfonyMailerStrategy implements MailerStrategyInterface
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var string|null
     */
    private ?string $defaultFrom;

    /**
     * @var string|null
     */
    private ?string $defaultReplyTo;

    /**
     * @param MailerInterface     $mailer
     * @param TranslatorInterface $translator
     * @param string|null         $defaultFrom
     * @param string|null         $defaultReplyTo
     */
    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        ?string $defaultFrom = null,
        ?string $defaultReplyTo = null
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->defaultFrom = $defaultFrom;
        $this->defaultReplyTo = $defaultReplyTo;
    }

    /**
     * @see We need to wait until Symfony resolve https://github.com/symfony/symfony/issues/35925
     *
     * @param MailMessageInterface $message
     *
     * @throws \Throwable
     */
    public function send(MailMessageInterface $message): void
    {
        $previousLocale = $this->translator->getLocale();
        $this->translator->setLocale($message->getLanguage()->getLanguage());

        try {
            $email = (new TemplatedEmail())
                ->to($message->getTo()->getValue())
                ->subject($message->getSubject())
                ->htmlTemplate($message->getTemplate())
                ->context($message->getParameters());

            if ($message->hasFrom()) {
                $email->from($message->getFrom()->getValue());
            } else if (!empty($this->defaultFrom)) {
                $email->from($this->defaultFrom);
            }

            if ($message->hasReplyTo()) {
                $email->replyTo($message->getReplyTo()->getValue());
            } else if (!empty($this->defaultReplyTo)) {
                $email->replyTo($this->defaultReplyTo);
            }

            $this->mailer->send($email);
        } catch (\Throwable $exception) {
            $this->translator->setLocale($previousLocale);
            throw $exception;
        }
    }
}
