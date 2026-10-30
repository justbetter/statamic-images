<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Data;

/**
 * @property array<int, array<string, int>> $dimensions
 * @property array<string, mixed> $params
 *
 * @extends Data<string, mixed>
 */
class Config extends Data
{
    protected array $rules = [
        'dimensions' => 'required',
        'dimensions.*.w' => 'required_without:dimensions.*.h',
        'dimensions.*.h' => 'required_without:dimensions.*.w',
        'params' => 'present',
    ];

    /** @param array<string, mixed> $data */
    public static function new(array $data): static
    {
        $data['params'] ??= [];

        return static::make($data)->validate();
    }
}
