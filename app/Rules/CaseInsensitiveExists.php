<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class CaseInsensitiveExists implements ValidationRule
{
    protected $table;
    protected $column;

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        ray($this->table);
        $exists = DB::table($this->table)
            ->whereRaw("LOWER({$this->column}) = ?", strtolower($value))
            ->exists();

        if(!$exists) {
            $fail("Email not found in our database");
        }
    }
}
