<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Sender\Strategy;

use Ergonode\Mailer\Domain\MailInterface;
use Ergonode\Mailer\Infrastructure\Sender\MailerStrategyInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;

class SymfonyMailerStrategy implements MailerStrategyInterface
{
    private MailerInterface $mailer;

    private LocaleAwareInterface $localeAware;

    private ?string $defaultFrom;

    private ?string $defaultReplyTo;

    public function __construct(
        MailerInterface $mailer,
        LocaleAwareInterface $localeAware,
        ?string $defaultFrom = null,
        ?string $defaultReplyTo = null
    ) {
        $this->mailer = $mailer;
        $this->localeAware = $localeAware;
        $this->defaultFrom = $defaultFrom;
        $this->defaultReplyTo = $defaultReplyTo;
    }

    /**
     * @see We need to wait until Symfony resolve https://github.com/symfony/symfony/issues/35925
     *
     *
     * @throws \Throwable
     */
    public function send(MailInterface $mail): void
    {
        $previousLocale = $this->localeAware->getLocale();
        $this->localeAware->setLocale($mail->getTemplate()->getLanguage()->getLanguageCode());

        try {
            $email = (new TemplatedEmail())
                ->addTo(...$mail->getRecipient()->getTo()->asStringArray())
                ->subject($mail->getSubject())
                ->htmlTemplate($mail->getTemplate()->getPath())
                ->context($mail->getTemplate()->getParameters());

            if ($mail->getSender()->hasFrom()) {
                $email->addFrom(...$mail->getSender()->getFrom()->asStringArray());
            } elseif (!empty($this->defaultFrom)) {
                $email->from($this->defaultFrom);
            }

            if ($mail->getSender()->hasReplyTo()) {
                $email->addReplyTo(...$mail->getSender()->getReplyTo()->asStringArray());
            } elseif (!empty($this->defaultReplyTo)) {
                $email->replyTo($this->defaultReplyTo);
            }

            if ($mail->getRecipient()->hasBcc()) {
                $email->addBcc(...$mail->getRecipient()->getBcc()->asStringArray());
            }

            if ($mail->getRecipient()->hasCc()) {
                $email->addCc(...$mail->getRecipient()->getCc()->asStringArray());
            }

            $this->mailer->send($email);
        } finally {
            $this->localeAware->setLocale($previousLocale);
        }
    }
}
