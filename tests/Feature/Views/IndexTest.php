<?php

namespace Savannabits\WebGenerator\Tests\Feature\Views;

use Savannabits\WebGenerator\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function index_listing_should_get_auto_generated(): void
    {
        $indexPath = resource_path('views/admin/category/index.blade.php');
        $listingJsPath = resource_path('js/admin/category/Listing.js');
        $indexJsPath = resource_path('js/admin/category/index.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        $this->assertFileNotExists($indexPath);
        $this->assertFileNotExists($listingJsPath);
        $this->assertFileNotExists($indexJsPath);

        $this->artisan('web:generate:index', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($indexPath);
        $this->assertFileExists($listingJsPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('@extends(\'web.layout.base.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../app-components/Listing/AppListing\';

Vue.component(\'category-listing\', {
    mixins: [AppListing]
});', File::get($listingJsPath));
        $this->assertStringStartsWith('import \'./Listing\'', File::get($indexJsPath));
        $this->assertStringStartsWith('import \'./category\';', File::get($bootstrapJsPath));
    }

    /** @test */
    public function index_listing_should_get_auto_generated_with_custom_model(): void
    {
        $indexPath = resource_path('views/admin/billing/my-article/index.blade.php');
        $listingJsPath = resource_path('js/admin/billing-my-article/Listing.js');
        $indexJsPath = resource_path('js/admin/billing-my-article/index.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        $this->assertFileNotExists($indexPath);
        $this->assertFileNotExists($listingJsPath);
        $this->assertFileNotExists($indexJsPath);


        $this->artisan('web:generate:index', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\MyArticle'
        ]);

        $this->assertFileExists($indexPath);
        $this->assertFileExists($listingJsPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('@extends(\'web.layout.base.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../app-components/Listing/AppListing\';

Vue.component(\'billing-my-article-listing\', {
    mixins: [AppListing]
});', File::get($listingJsPath));

        $this->assertStringStartsWith('import \'./Listing\';', File::get($indexJsPath));
        $this->assertStringStartsWith('import \'./billing-my-article\';', File::get($bootstrapJsPath));
    }
}
