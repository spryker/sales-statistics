<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacade;
use Spryker\Zed\SalesStatistics\Communication\Plugin\TopOrdersChartPlugin;
use SprykerTest\Zed\SalesStatistics\SalesStatisticsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesStatistics
 * @group Communication
 * @group Plugin
 * @group TopOrdersChartPluginTest
 * Add your own group annotations below this line
 */
class TopOrdersChartPluginTest extends Unit
{
    protected const string DATA_IDENTIFIER = 'top-orders';

    protected SalesStatisticsCommunicationTester $tester;

    public function testGetChartDataShowsNoMoreProductsThanTheConstantOverriddenInASubclass(): void
    {
        // Arrange
        $this->tester->haveOrderWithItemNamed('sales-statistics-test-product-a');
        $this->tester->haveOrderWithItemNamed('sales-statistics-test-product-b');
        $this->tester->haveOrderWithItemNamed('sales-statistics-test-product-c');
        $topOrdersChartPlugin = new class extends TopOrdersChartPlugin {
            public const COUNT_PRODUCT = 2;
        };
        $topOrdersChartPlugin->setFacade(new SalesStatisticsFacade());

        // Act
        $chartDataTransfer = $topOrdersChartPlugin->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $chartDataTraceTransfer = $chartDataTransfer->getTraces()->offsetGet(0);
        $this->assertCount(2, $chartDataTraceTransfer->getLabels());
        $this->assertCount(2, $chartDataTraceTransfer->getValues());
    }

    public function testGetChartDataDescribesTheRankingChart(): void
    {
        // Arrange
        $this->tester->haveOrderWithItemNamed('sales-statistics-test-product-a');

        // Act
        $chartDataTransfer = (new TopOrdersChartPlugin())->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $chartDataTraceTransfer = $chartDataTransfer->getTraces()->offsetGet(0);
        $this->assertSame(static::DATA_IDENTIFIER, $chartDataTransfer->getKey());
        $this->assertSame(TopOrdersChartPlugin::TITLE, $chartDataTransfer->getTitle());
        $this->assertSame('ranking', $chartDataTransfer->getView());
        $this->assertSame(ChartConfig::CHART_TYPE_BAR, $chartDataTraceTransfer->getType());
        $this->assertContains(TopOrdersChartPlugin::OPTIONS, $chartDataTraceTransfer->getOptions());
    }
}
