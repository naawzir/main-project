<?php

namespace App\Rules;

use App\Solicitor;
use App\SolicitorOffice;
use Illuminate\Contracts\Validation\Rule;

class OfficeName implements Rule
{
    /** @var Solicitor */
    private $solicitor;
    /** @var SolicitorOffice */
    private $office;

    public function __construct(Solicitor $solicitor, SolicitorOffice $office = null)
    {
        $this->solicitor = $solicitor;
        $this->office = $office;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $conditions = $this->conditions($value);

        return !$this->solicitor->offices()
            ->where($conditions)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The office must have a unique name.';
    }

    private function conditions(string $value): array
    {
        $conditions = [
            ['office_name', '=', $value],
            ['solicitor_id', '=', $this->solicitor->id],
        ];
        if (null !== $this->office) {
            $conditions[] = ['id', '!=', $this->office->id];
        }

        return $conditions;
    }
}
