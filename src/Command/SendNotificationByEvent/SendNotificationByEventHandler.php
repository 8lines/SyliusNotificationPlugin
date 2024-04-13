<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent;

final class SendNotificationByEventHandler
{
    public function __construct(
        private SendNotificationByEventInterface $sendNotificationByEvent,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(SendNotificationByEventCommand $command): void
    {
        $this->sendNotificationByEvent->sendNotificationByEvent(
            eventCode: $command->getEventName(),
            subject: $command->getSubject(),
            invoker: $command->getInvoker(),
        );
    }
}
