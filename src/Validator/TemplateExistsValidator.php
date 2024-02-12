<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Validator;

use EightLines\SyliusNotificationPlugin\Validator\Constraint\TemplateExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Twig\Environment;

final class TemplateExistsValidator extends ConstraintValidator
{
    public function __construct(

        private Environment $environment,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $constraint instanceof TemplateExists) {
            throw new UnexpectedTypeException($constraint, TemplateExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (false === \is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (true === $this->environment->getLoader()->exists($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation()
        ;
    }
}
