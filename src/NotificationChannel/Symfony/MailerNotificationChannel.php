<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationBody;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationRecipient;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class MailerNotificationChannel implements NotificationChannelInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $environment,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws TransportExceptionInterface
     */
    public function send(
        ?NotificationRecipient $recipient,
        NotificationBody $body,
        NotificationContext $context,
    ): void {
        $emailTo = $recipient?->getEmail();

        if (null === $emailTo) {
            throw new \InvalidArgumentException('Recipient email is required');
        }

        $configuration = $context->getConfiguration();

        $emailSubject = $body->getSubject();
        $emailMessage = $body->getMessage();

        /** @var string|null $emailTemplate */
        $emailTemplate = $configuration->getCustomValue('template');

        /** @var string|null $emailFrom */
        $emailFrom = $configuration->getCustomValue('from');

        $email = new Email();
        $email->to($emailTo);

        if (null !== $emailSubject) {
            $email->subject($emailSubject);
        }

        if (null !== $emailFrom) {
            $email->from($emailFrom);
        }

        if (null !== $emailTemplate) {
            $email->html($this->environment->render($emailTemplate, [
                'recipient' => $recipient,
                'body' => $body,
                'context' => $context,
            ]));
        } else if (null !== $emailMessage) {
            $email->text($emailMessage);
        }

        $this->mailer->send($email);
    }

    public static function getIdentifier(): string
    {
        return 'mailer';
    }

    public static function supportsUnknownRecipient(): bool
    {
        return false;
    }

    public static function getConfigurationFormType(): ?string
    {
        return MailerNotificationChannelFormType::class;
    }

    public static function supports(): bool
    {
        return true;
    }
}
