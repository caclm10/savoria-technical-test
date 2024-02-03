<?php

function retrieveOldsPost(): array | null
{
    $old = session("_ci_old_input");
    return $old ? $old["post"] : null;
}

function retrieveValidationErrors(): array | null
{
    $errors = session("_ci_validation_errors");

    return $errors;
}

function getValidationError(string $name): string
{
    $errors = retrieveValidationErrors();

    if (!$errors) return "";

    return array_key_exists($name, $errors) ? $errors[$name] : "";
}

function hasValidationError(string $name): bool
{
    $errors = retrieveValidationErrors();

    if (!$errors) return false;

    return array_key_exists($name, $errors);
}
