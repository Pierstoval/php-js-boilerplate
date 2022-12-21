<?php

namespace App\Tests\Admin;

use App\Controller\Admin\BookCrudController;
use App\DataFixtures\BookFixtures;
use Pierstoval\SmokeTesting\FunctionalSmokeTester;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class AdminBooksTest extends WebTestCase
{
    use FunctionalSmokeTester;
    use AdminListTestUtils;

    public function testList(): void
    {
        $this->runFunctionalTest(
            $this->getTestDataForActionAndCrudController('index', BookCrudController::class)
                ->expectStatusCode(200)
                ->appendCallableExpectation(fn($_, Crawler $crawler) => $this->setCrawler($crawler))
                ->appendCallableExpectation(fn() => $this->assertListHasXElements(1))
                ->appendCallableExpectation(fn() => $this->assertElementXIdentifierIs(0, BookFixtures::BOOK_ID))
                ->appendCallableExpectation(fn() => $this->assertElementXFieldIs(0, 'Title', 'Test book'))
        );
    }
}