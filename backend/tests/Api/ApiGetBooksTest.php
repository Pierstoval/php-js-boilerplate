<?php

namespace App\Tests\Api;

use Pierstoval\SmokeTesting\FunctionalSmokeTester;
use Pierstoval\SmokeTesting\FunctionalTestData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiGetBooksTest extends WebTestCase
{
    use FunctionalSmokeTester;

    public function testGetBook(): void
    {
        $this->runFunctionalTest(
            FunctionalTestData::withUrl('/api/books/96ba14a6-f7d3-42fb-b874-548d4d0f55c5')
            ->expectStatusCode(200)
            ->expectJsonParts([
                'id' => '96ba14a6-f7d3-42fb-b874-548d4d0f55c5',
                'title' => 'Test book',
            ])
        );
    }
}
