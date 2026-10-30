<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JustBetter\StatamicImages\ServiceProvider;
use Statamic\Assets\Asset;
use Statamic\Assets\AssetContainer;
use Statamic\Facades\AssetContainer as AssetContainerFacade;
use Statamic\Testing\AddonTestCase;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

abstract class TestCase extends AddonTestCase
{
    use PreventsSavingStacheItemsToDisk;

    protected string $addonServiceProvider = ServiceProvider::class;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('assets', ['url' => '/assets']);

        /** @var AssetContainer $container */
        $container = AssetContainerFacade::make('assets');
        $container->disk('assets');
        $container->save();
    }

    protected function createAsset(string $filename, int $width = 1200, int $height = 800): Asset
    {
        $contents = str_ends_with($filename, '.svg')
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'"></svg>'
            : UploadedFile::fake()->image($filename, $width, $height)->getContent();

        Storage::disk('assets')->put($filename, $contents);

        /** @var AssetContainer $container */
        $container = AssetContainerFacade::findByHandle('assets');

        /** @var Asset $asset */
        $asset = $container->makeAsset($filename);
        $asset->save();

        return $asset;
    }
}
