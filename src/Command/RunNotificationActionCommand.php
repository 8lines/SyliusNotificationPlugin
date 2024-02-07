<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command;

use EightLines\SyliusNotificationPlugin\Entity\NotificationActionInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;

final class RunNotificationActionCommand
{
    public function __construct(
        private NotificationContext $context,
        private NotificationActionInterface $action,
        private NotificationEventVariables $variables,
    ) {
    }

    public function getContext(): NotificationContext
    {
        return $this->context;
    }

    public function getAction(): NotificationActionInterface
    {
        return $this->action;
    }

    public function getVariables(): NotificationEventVariables
    {
        return $this->variables;
    }
}
