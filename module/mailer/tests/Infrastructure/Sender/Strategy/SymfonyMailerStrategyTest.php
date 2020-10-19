<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Infrastructure\Sender\Strategy;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Mailer\Domain\MailInterface;
use Ergonode\Mailer\Domain\Recipient;
use Ergonode\Mailer\Domain\Sender;
use Ergonode\Mailer\Domain\Template;
use Ergonode\Mailer\Infrastructure\Sender\Strategy\SymfonyMailerStrategy;
use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use PHPStan\Testing\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Translation\Translator;

/**
 */
final class SymfonyMailerStrategyTest extends TestCase
{
    /**
     */
    public function testHandling(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send');

        $translator = $this->createMock(Translator::class);
        $translator->expects(self::atMost(2))->method('setLocale');
        $translator->expects(self::once())->method('getLocale')->willReturn('en_US');

        $emailCollection = $this->createMock(EmailCollection::class);
        $emailCollection->expects(self::atMost(5))->method('asStringArray')->willReturn(['test@ergonode.com']);

        $recipient = $this->createMock(Recipient::class);
        $recipient->expects(self::once())->method('getTo')->willReturn($emailCollection);
        $recipient->expects(self::once())->method('hasBcc')->willReturn(true);
        $recipient->expects(self::once())->method('getBcc')->willReturn($emailCollection);
        $recipient->expects(self::once())->method('hasCc')->willReturn(true);
        $recipient->expects(self::once())->method('getCc')->willReturn($emailCollection);

        $sender = $this->createMock(Sender::class);
        $sender->expects(self::once())->method('hasFrom')->willReturn(true);
        $sender->expects(self::once())->method('getFrom')->willReturn($emailCollection);
        $sender->expects(self::once())->method('hasReplyTo')->willReturn(true);
        $sender->expects(self::once())->method('getReplyTo')->willReturn($emailCollection);

        $language = $this->createMock(Language::class);
        $language->expects(self::once())->method('getLanguageCode')->willReturn('en');

        $template = $this->createMock(Template::class);
        $template->expects(self::once())->method('getPath')->willReturn('ergonode');
        $template->expects(self::once())->method('getParameters')->willReturn([]);
        $template->expects(self::once())->method('getLanguage')->willReturn($language);

        $mail = $this->createMock(MailInterface::class);
        $mail->expects(self::atLeastOnce())->method('getRecipient')->willReturn($recipient);
        $mail->expects(self::atLeastOnce())->method('getSender')->willReturn($sender);
        $mail->expects(self::atLeastOnce())->method('getTemplate')->willReturn($template);
        $mail->expects(self::once())->method('getSubject');

        $symfonyMailer = new SymfonyMailerStrategy($mailer, $translator);
        $symfonyMailer->send($mail);
    }
}
