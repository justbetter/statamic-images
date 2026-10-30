<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Tests\Data;

use Illuminate\Validation\ValidationException;
use Iterator;
use JustBetter\StatamicImages\Data\Config;
use JustBetter\StatamicImages\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class ConfigTest extends TestCase
{
    #[Test]
    public function it_defaults_params(): void
    {
        $config = Config::new(['dimensions' => [['w' => 100]]]);

        $this->assertSame([], $config->params);
    }

    /** @param  array<string, mixed>  $data */
    #[Test]
    #[DataProvider('invalid')]
    public function it_validates(array $data): void
    {
        $this->expectException(ValidationException::class);

        Config::new($data);
    }

    public static function invalid(): Iterator
    {
        yield 'missing dimensions' => [[]];
        yield 'dimension without width or height' => [['dimensions' => [['q' => 1]]]];
    }
}
