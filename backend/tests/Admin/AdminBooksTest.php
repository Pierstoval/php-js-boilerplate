<?php

namespace App\Tests\Admin;

use App\Controller\Admin\BookCrudController;
use App\DataFixtures\BookFixtures;
use Pierstoval\SmokeTesting\FunctionalSmokeTester;
use Pierstoval\SmokeTesting\FunctionalTestData;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class AdminBooksTest extends WebTestCase
{
    use FunctionalSmokeTester;

    public function testList(): void
    {
        $this->runFunctionalTest(
            $this->getTestDataForActionAndCrudController('index', BookCrudController::class)
                ->expectStatusCode(200)
                ->appendCallableExpectation(function(KernelBrowser $browser, Crawler $crawler) {
                    $this->assertListHasXElements($crawler, 1);
                    $this->assertElementXIdentifierIs($crawler, 0, BookFixtures::BOOK_ID);
                    $this->assertElementXFieldIs($crawler, 0, 'Title', 'Test book');
                })
        );
    }

    protected function assertListHasXElements(Crawler $crawler, int $numberOfElements): void
    {
        $this->assertCount($numberOfElements, $this->getHtmlElementsFromCrawler($crawler));
    }

    protected function assertElementXIdentifierIs(Crawler $crawler, int $elementIndex, string $expectedId): void
    {
        $this->assertSame($expectedId, $this->getHtmlElementsFromCrawler($crawler)->eq($elementIndex)->attr('data-id'));
    }

    protected function assertElementXFieldIs(Crawler $crawler, int $elementIndex, string $fieldName, string $expectedValue)
    {
        $this->assertSame(
            $expectedValue,
            $this->getHtmlElementsFromCrawler($crawler)
                ->eq($elementIndex)
                ->filter(\sprintf("td[data-label=\"%s\"]", $fieldName))
                ->text()
        );
    }

    protected function getHtmlElementsFromCrawler(Crawler $crawler): Crawler
    {
        return $crawler->filter('#main table.datagrid tbody tr');
    }

    protected function getTestDataForActionAndCrudController(string $action, $crudController): FunctionalTestData
    {
        $url = \sprintf('/admin?crudAction=%s&crudControllerFqcn=%s', $action, $crudController);

        return FunctionalTestData::withUrl($url);
    }
}