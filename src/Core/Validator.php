<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Validateur d'entrees generique, base sur un jeu de regles nommees
 * appliquees a un tableau de donnees (ex : $_POST).
 *
 * Usage :
 *   $validator = new Validator($_POST, [
 *       'email' => ['required', 'email'],
 *       'nb_places_total' => ['required', 'integer:1,50'],
 *   ]);
 *   if (!$validator->passes()) { $errors = $validator->errors(); }
 */
final class Validator
{
    /** @var array<string, mixed> */
    private array $data;

    /** @var array<string, array<int, string>> */
    private array $rules;

    /** @var array<string, array<int, string>> */
    private array $errors = [];

    /**
     * @param array<string, mixed> $data
     * @param array<string, array<int, string>> $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function passes(): bool
    {
        $this->errors = [];

        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            $value = is_string($value) ? trim($value) : $value;

            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return $this->errors === [];
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }

        return null;
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        [$name, $parameter] = array_pad(explode(':', $rule, 2), 2, null);

        $isEmpty = $value === null || $value === '';

        match ($name) {
            'required' => $isEmpty && $this->addError($field, "Le champ {$field} est obligatoire."),
            'email' => !$isEmpty && !filter_var($value, FILTER_VALIDATE_EMAIL)
                && $this->addError($field, "L'adresse email est invalide."),
            'integer' => !$isEmpty && !$this->checkInteger((string) $value, $parameter)
                && $this->addError($field, "Le champ {$field} doit etre un nombre entier valide."),
            'date' => !$isEmpty && !$this->checkDate((string) $value)
                && $this->addError($field, "Le champ {$field} doit etre une date valide."),
            'max' => !$isEmpty && mb_strlen((string) $value) > (int) $parameter
                && $this->addError($field, "Le champ {$field} ne doit pas depasser {$parameter} caracteres."),
            'min' => !$isEmpty && mb_strlen((string) $value) < (int) $parameter
                && $this->addError($field, "Le champ {$field} doit contenir au moins {$parameter} caracteres."),
            'phone' => !$isEmpty && !preg_match('/^[0-9+().\-\s]{6,20}$/', (string) $value)
                && $this->addError($field, "Le numero de telephone est invalide."),
            default => null,
        };
    }

    private function checkInteger(string $value, ?string $range): bool
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            return false;
        }

        if ($range === null) {
            return true;
        }

        [$min, $max] = array_pad(explode(',', $range, 2), 2, null);
        $intValue = (int) $value;

        if ($min !== null && $intValue < (int) $min) {
            return false;
        }

        if ($max !== null && $intValue > (int) $max) {
            return false;
        }

        return true;
    }

    private function checkDate(string $value): bool
    {
        return \DateTime::createFromFormat('Y-m-d\TH:i', $value) !== false
            || \DateTime::createFromFormat('Y-m-d H:i:s', $value) !== false
            || \DateTime::createFromFormat('Y-m-d H:i', $value) !== false;
    }

    private function addError(string $field, string $message): bool
    {
        $this->errors[$field][] = $message;

        return true;
    }
}
