<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use EightLines\SyliusNotificationPlugin\Validator\TemplateExistsValidator;

final class TemplateExists extends Constraint
{
    public string $message = 'The string "{{ string }}" is not a valid template.';

    public function __construct(
        mixed $options = null,
        string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options ?? [], $groups, $payload);

        $this->message = $message ?? $this->message;
    }

    public function validatedBy(): string
    {
        return TemplateExistsValidator::class;
    }
}
