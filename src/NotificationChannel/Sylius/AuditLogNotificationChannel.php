<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Sylius;

use Doctrine\ORM\EntityManagerInterface;
use EightLines\SyliusNotificationPlugin\Factory\AuditLogFactoryInterface;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannel\MessageWithoutRecipientType;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationBody;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationRecipient;

final class AuditLogNotificationChannel implements NotificationChannelInterface
{
    public function __construct(
        private AuditLogFactoryInterface $auditLogFactory,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function send(
        ?NotificationRecipient $recipient,
        NotificationBody $body,
        NotificationContext $context,
    ): void {
        $content = $body->getMessage();

        if (null === $content) {
            throw new \InvalidArgumentException('Content is required');
        }

        $auditLog = $this->auditLogFactory->create(
            content: $content,
            eventName: $context->getEventName(),
            context: $context->getEventLevelContext()->getSubject(),
            invoker: $context->getSyliusInvoker(),
            channel: $context->getSyliusChannel(),
        );

        $this->entityManager->persist($auditLog);
        $this->entityManager->flush();
    }

    public static function getIdentifier(): string
    {
        return 'audit-log';
    }

    public static function supportsUnknownRecipient(): bool
    {
        return true;
    }

    public static function getConfigurationFormType(): ?string
    {
        return MessageWithoutRecipientType::class;
    }

    public static function supports(): bool
    {
        return true;
    }
}
