<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Translation\Translator;

final class SymfonyMailerStrategyTest extends TestCase
{
    public function testHandling(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send');

        $translator = $this->createMock(Translator::class);
        $translator->expects($this->atMost(2))->method('setLocale');
        $translator->expects($this->once())->method('getLocale')->willReturn('en_US');

        $emailCollection = $this->createMock(EmailCollection::class);
        $emailCollection->expects($this->atMost(5))->method('asStringArray')->willReturn(['test@ergonode.com']);

        $recipient = $this->createMock(Recipient::class);
        $recipient->expects($this->once())->method('getTo')->willReturn($emailCollection);
        $recipient->expects($this->once())->method('hasBcc')->willReturn(true);
        $recipient->expects($this->once())->method('getBcc')->willReturn($emailCollection);
        $recipient->expects($this->once())->method('hasCc')->willReturn(true);
        $recipient->expects($this->once())->method('getCc')->willReturn($emailCollection);

        $sender = $this->createMock(Sender::class);
        $sender->expects($this->once())->method('hasFrom')->willReturn(true);
        $sender->expects($this->once())->method('getFrom')->willReturn($emailCollection);
        $sender->expects($this->once())->method('hasReplyTo')->willReturn(true);
        $sender->expects($this->once())->method('getReplyTo')->willReturn($emailCollection);

        $language = $this->createMock(Language::class);
        $language->expects($this->once())->method('getLanguageCode')->willReturn('en');

        $template = $this->createMock(Template::class);
        $template->expects($this->once())->method('getPath')->willReturn('ergonode');
        $template->expects($this->once())->method('getParameters')->willReturn([]);
        $template->expects($this->once())->method('getLanguage')->willReturn($language);

        $mail = $this->createMock(MailInterface::class);
        $mail->expects($this->atLeastOnce())->method('getRecipient')->willReturn($recipient);
        $mail->expects($this->atLeastOnce())->method('getSender')->willReturn($sender);
        $mail->expects($this->atLeastOnce())->method('getTemplate')->willReturn($template);
        $mail->expects($this->once())->method('getSubject');

        $symfonyMailer = new SymfonyMailerStrategy($mailer, $translator);
        $symfonyMailer->send($mail);
    }
}
