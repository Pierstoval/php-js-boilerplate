<?php

namespace App\Tests\Admin;

use Pierstoval\SmokeTesting\FunctionalTestData;
use Symfony\Component\DomCrawler\Crawler;

trait AdminListTestUtils
{
    private ?Crawler $crawler = null;

    protected function setCrawler(Crawler $crawler): void
    {
        $this->crawler = $crawler;
    }

    private function getCrawler(): Crawler
    {
        if (!isset($this->crawler)) {
            throw new \RuntimeException('Crawler has not been set.'."\n".'You must set it in the tester via this kind of code:'."\n".'$testData->appendCallableExpectation(fn($_, Crawler $crawler) => $this->setCrawler($crawler))');
        }

        return $this->crawler;
    }

    protected function assertListHasXElements(int $numberOfElements): void
    {
        $this->assertCount($numberOfElements, $this->getTableList());
    }

    protected function assertElementXIdentifierIs(int $elementIndex, string $expectedId): void
    {
        $this->assertSame($expectedId, $this->getTableList()->eq($elementIndex)->attr('data-id'));
    }

    protected function assertElementXFieldIs(int $elementIndex, string $fieldName, string $expectedValue): void
    {
        $this->assertSame($expectedValue,
            $this->getTableList()
                ->eq($elementIndex)
                ->filter(\sprintf('td[data-label="%s"]', $fieldName))
                ->text()
        );
    }

    protected function getTableList(): Crawler
    {
        return $this->getCrawler()->filter('#main table.datagrid tbody tr');
    }

    protected function getTestDataForActionAndCrudController(string $action, $crudController): FunctionalTestData
    {
        $url = \sprintf('/admin?crudAction=%s&crudControllerFqcn=%s', $action, $crudController);

        return FunctionalTestData::withUrl($url)
            ->appendCallableExpectation(fn ($_, Crawler $crawler) => $this->setCrawler($crawler));
    }
}
