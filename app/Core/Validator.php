<?php

namespace App\Core;

class Validator
{
    private $errors = [];

    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $ruleName = $rule;
                if (is_string($rule)) {
                    if (strpos($rule, ':') !== false) {
                        [$ruleName, $param] = explode(':', $rule, 2);
                    }
                }

                $this->applyRule($field, $value, $ruleName, $param ?? null);
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, $value, string $ruleName, $param = null): void
    {
        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, 'This field is required.');
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'This field must be a valid email address.');
                }
                break;
            case 'min':
                if (strlen($value) < $param) {
                    $this->addError($field, "This field must be at least {$param} characters.");
                }
                break;
            case 'confirmed':
                $confirmedField = $field . '_confirmation';
                if ($value !== ($_POST[$confirmedField] ?? null)) {
                    $this->addError($field, 'This field does not match the confirmation field.');
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }
}
