<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacade;
use Spryker\Zed\SalesStatistics\Communication\Plugin\CountOrderChartPlugin;
use SprykerTest\Zed\SalesStatistics\SalesStatisticsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesStatistics
 * @group Communication
 * @group Plugin
 * @group CountOrderChartPluginTest
 * Add your own group annotations below this line
 */
class CountOrderChartPluginTest extends Unit
{
    protected const string DATA_IDENTIFIER = 'count-orders';

    protected SalesStatisticsCommunicationTester $tester;

    public function testGetChartDataCountsOrdersWithinTheDaysOfTheConstant(): void
    {
        // Arrange
        $countOrderChartPlugin = new CountOrderChartPlugin();
        $orderCountByDateBefore = $this->getOrderCountByDate($countOrderChartPlugin);
        $this->tester->haveOrderCreatedDaysAgo(3);

        // Act
        $orderCountByDateAfter = $this->getOrderCountByDate($countOrderChartPlugin);

        // Assert
        $threeDaysAgo = $this->tester->getDateDaysAgo(3);
        $this->assertSame(($orderCountByDateBefore[$threeDaysAgo] ?? 0) + 1, $orderCountByDateAfter[$threeDaysAgo]);
    }

    public function testGetChartDataReadsTheDaysConstantOverriddenInASubclass(): void
    {
        // Arrange
        $countOrderChartPlugin = new class extends CountOrderChartPlugin {
            public const DAYS = 1;
        };
        $countOrderChartPlugin->setFacade(new SalesStatisticsFacade());
        $orderCountByDateBefore = $this->getOrderCountByDate($countOrderChartPlugin);
        $this->tester->haveOrderCreatedDaysAgo(3);

        // Act
        $orderCountByDateAfter = $this->getOrderCountByDate($countOrderChartPlugin);

        // Assert
        $this->assertArrayNotHasKey($this->tester->getDateDaysAgo(3), $orderCountByDateAfter);
        $this->assertSame($orderCountByDateBefore, $orderCountByDateAfter);
    }

    public function testGetChartDataSetsNoViewSoTheChartKeepsTheDefaultRendering(): void
    {
        // Act
        $chartDataTransfer = (new CountOrderChartPlugin())->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $this->assertSame(static::DATA_IDENTIFIER, $chartDataTransfer->getKey());
        $this->assertSame(CountOrderChartPlugin::TITLE, $chartDataTransfer->getTitle());
        $this->assertNull($chartDataTransfer->getView());
        $this->assertSame(ChartConfig::CHART_TYPE_BAR, $chartDataTransfer->getTraces()->offsetGet(0)->getType());
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOrderCountByDate(CountOrderChartPlugin $countOrderChartPlugin): array
    {
        $chartDataTransfer = $countOrderChartPlugin->getChartData(static::DATA_IDENTIFIER);

        return $this->tester->getValuesIndexedByLabel($chartDataTransfer->getTraces()->offsetGet(0));
    }
}
