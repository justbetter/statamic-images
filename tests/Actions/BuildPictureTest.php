<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Tests\Actions;

use Iterator;
use JustBetter\StatamicImages\Contracts\BuildsPicture;
use JustBetter\StatamicImages\Data\Config;
use JustBetter\StatamicImages\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class BuildPictureTest extends TestCase
{
    /**
     * @param  array<int, array<string, int>>  $dimensions
     * @param  array<int, string>  $widths
     */
    #[Test]
    #[DataProvider('dimensions')]
    public function it_builds_pictures(array $dimensions, array $widths, int $width, int $height): void
    {
        $picture = app(BuildsPicture::class)->build(
            $this->createAsset('image.jpg'),
            Config::new(['dimensions' => $dimensions]),
        );

        $candidates = collect(explode(', ', $picture->sources['image/webp']))
            ->map(fn (string $candidate): string => str($candidate)->afterLast(' ')->toString())
            ->all();

        $this->assertSame($widths, $candidates);
        $this->assertSame($width, $picture->width);
        $this->assertSame($height, $picture->height);
    }

    public static function dimensions(): Iterator
    {
        yield 'width only' => [[['w' => 600]], ['600w', '1200w'], 600, 400];
        yield 'height only' => [[['h' => 400]], ['600w', '1200w'], 600, 400];
        yield 'width and height' => [[['w' => 460, 'h' => 350]], ['460w', '920w'], 460, 350];
        yield '2x exceeds max size' => [[['w' => 2000]], ['2000w'], 2000, 1333];
        yield 'multiple dimensions' => [
            [['w' => 390, 'h' => 475], ['w' => 460, 'h' => 350]],
            ['390w', '780w', '460w', '920w'],
            460,
            350,
        ];
    }

    #[Test]
    public function it_builds_a_source_per_format(): void
    {
        config()->set('statamic-glide-images.formats', [
            'webp' => 'image/webp',
            'jpg' => 'image/jpeg',
        ]);

        $picture = app(BuildsPicture::class)->build(
            $this->createAsset('image.jpg'),
            Config::new(['dimensions' => [['w' => 600]]]),
        );

        $this->assertSame(['image/webp', 'image/jpeg'], array_keys($picture->sources));
        $this->assertStringContainsString('fm=jpg', $picture->src);
    }

    #[Test]
    public function it_passes_params_to_glide(): void
    {
        $picture = app(BuildsPicture::class)->build(
            $this->createAsset('image.jpg'),
            Config::new([
                'dimensions' => [['w' => 600]],
                'params' => ['quality' => 50],
            ]),
        );

        $this->assertStringContainsString('q=50', $picture->src);
    }
}
