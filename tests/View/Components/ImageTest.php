<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Tests\View\Components;

use JustBetter\StatamicImages\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ImageTest extends TestCase
{
    #[Test]
    public function it_renders_images(): void
    {
        $view = $this->blade('<x-justbetter::image :image="$image" class="::class::" />', [
            'image' => $this->createAsset('image.jpg'),
        ]);

        $view->assertSee('<source', false)
            ->assertSee('type="image/webp"', false)
            ->assertSee('sizes="auto, 100vw"', false)
            ->assertSee('width="960"', false)
            ->assertSee('loading="lazy"', false)
            ->assertSee('class="::class::"', false);
    }

    #[Test]
    public function it_renders_a_source_per_media(): void
    {
        $view = $this->blade('<x-justbetter::image :image="$image" :dimensions="$dimensions" />', [
            'image' => $this->createAsset('image.jpg'),
            'dimensions' => [
                ['w' => 390, 'h' => 475],
                ['w' => 460, 'h' => 350, 'media' => '(max-width: 999px)'],
            ],
        ]);

        $view->assertSeeInOrder(['media="(max-width: 999px)"', 'width="460"', '<source', 'width="390"', '<img'], false)
            ->assertSee('height="475" loading="lazy"', false);
    }

    #[Test]
    public function it_renders_svg_images(): void
    {
        $view = $this->blade('<x-justbetter::image :image="$image" />', [
            'image' => $this->createAsset('logo.svg'),
        ]);

        $view->assertDontSee('<source', false)
            ->assertSee('src="/assets/logo.svg"', false);
    }
}
