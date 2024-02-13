<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Factory;

use EightLines\SyliusNotificationPlugin\Entity\AuditLog;
use EightLines\SyliusNotificationPlugin\Entity\AuditLogChannel;
use EightLines\SyliusNotificationPlugin\Entity\AuditLogInvoker;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AuditLogFactory implements AuditLogFactoryInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
    ) {
    }

    public function create(
        string $content,
        string $eventName,
        mixed $context,
        CustomerInterface|AdminUserInterface|null $invoker,
        ?ChannelInterface $channel,
    ): AuditLog {
        $auditLog = new AuditLog();
        $auditLog->setContent($content);
        $auditLog->setEventCode($eventName);

        try {
            /** @var array $eventContext */
            $eventContext = $this->normalizer->normalize($context);
        } catch (ExceptionInterface) {
        }

        $auditLog->setContext($eventContext ?? null);

        $auditLogChannel = new AuditLogChannel();
        $auditLogChannel->setId((int) $channel?->getId());
        $auditLogChannel->setName($channel?->getName());

        $auditLog->setInvoker($this->getAuditLogInvoker($invoker));
        $auditLog->setChannel($auditLogChannel);

        return $auditLog;
    }

    public function createNew(): AuditLog
    {
        return new AuditLog();
    }

    private function getAuditLogInvoker(AdminUserInterface|CustomerInterface|null $invoker): AuditLogInvoker
    {
        $auditLogInvoker = new AuditLogInvoker();

        if (null === $invoker) {
            return $auditLogInvoker;
        }

        $auditLogInvoker->setId((int)$invoker->getId());

        if ($invoker instanceof CustomerInterface) {
            $auditLogInvoker->setType(AuditLogInvoker::CUSTOMER);
            $auditLogInvoker->setFullName($invoker->getFullName());

            return $auditLogInvoker;
        }

        $auditLogInvoker->setType(AuditLogInvoker::ADMIN_USER);

        $firstName = $invoker->getFirstName();
        $lastName = $invoker->getLastName();

        if (null !== $firstName || null !== $lastName) {
            $auditLogInvoker->setFullName(sprintf('%s %s',
                $firstName,
                $lastName,
            ));
        }

        return $auditLogInvoker;
    }
}
