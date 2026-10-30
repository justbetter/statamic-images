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
    public function it_renders_svg_images(): void
    {
        $view = $this->blade('<x-justbetter::image :image="$image" />', [
            'image' => $this->createAsset('logo.svg'),
        ]);

        $view->assertDontSee('<source', false)
            ->assertSee('src="/assets/logo.svg"', false);
    }
}
