<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Sender\Strategy;

use Ergonode\Mailer\Domain\MailInterface;
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
     * @param MailInterface $mail
     *
     * @throws \Throwable
     */
    public function send(MailInterface $mail): void
    {
        $previousLocale = $this->translator->getLocale();
        $this->translator->setLocale($mail->getTemplate()->getLanguage()->getLanguageCode());

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
            $this->translator->setLocale($previousLocale);
        }
    }
}
