<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Data;

use Illuminate\Support\Fluent;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Fluent<TKey, TValue>
 */
abstract class Data extends Fluent
{
    /** @var array <string, string> */
    protected array $rules = [];

    public function validate(): static
    {
        validator($this->attributes, $this->rules)->validate();

        return $this;
    }
}
