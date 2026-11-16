<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Zed\SalesStatistics\Communication\Plugin\DailyOrderCountChartPlugin;
use SprykerTest\Zed\SalesStatistics\SalesStatisticsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesStatistics
 * @group Communication
 * @group Plugin
 * @group DailyOrderCountChartPluginTest
 * Add your own group annotations below this line
 */
class DailyOrderCountChartPluginTest extends Unit
{
    protected const string DATA_IDENTIFIER = 'daily-order-count';

    protected SalesStatisticsCommunicationTester $tester;

    public function testGetChartDataReturnsOneLabelPerConfiguredDayEndingToday(): void
    {
        // Arrange
        $dailyOrderCountChartPlugin = $this->createDailyOrderCountChartPluginShowingDays(3);

        // Act
        $chartDataTransfer = $dailyOrderCountChartPlugin->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $chartDataTraceTransfer = $chartDataTransfer->getTraces()->offsetGet(0);
        $this->assertSame(
            [$this->tester->getDateDaysAgo(2), $this->tester->getDateDaysAgo(1), $this->tester->getDateDaysAgo(0)],
            $chartDataTraceTransfer->getLabels(),
        );
        $this->assertCount(3, $chartDataTraceTransfer->getValues());
    }

    public function testGetChartDataDescribesTheDailyBarChart(): void
    {
        // Arrange
        $dailyOrderCountChartPlugin = $this->createDailyOrderCountChartPluginShowingDays(3);

        // Act
        $chartDataTransfer = $dailyOrderCountChartPlugin->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $this->assertSame(static::DATA_IDENTIFIER, $chartDataTransfer->getKey());
        $this->assertSame(DailyOrderCountChartPlugin::TITLE, $chartDataTransfer->getTitle());
        $this->assertSame('daily-bar', $chartDataTransfer->getView());
        $this->assertCount(1, $chartDataTransfer->getTraces());
        $this->assertSame(ChartConfig::CHART_TYPE_BAR, $chartDataTransfer->getTraces()->offsetGet(0)->getType());
    }

    public function testGetChartDataCountsOrdersOnTheDayTheyWereCreated(): void
    {
        // Arrange
        $dailyOrderCountChartPlugin = $this->createDailyOrderCountChartPluginShowingDays(3);
        $orderCountByDateBefore = $this->getOrderCountByDate($dailyOrderCountChartPlugin);
        $this->tester->haveOrderCreatedDaysAgo(0);
        $this->tester->haveOrderCreatedDaysAgo(0);
        $this->tester->haveOrderCreatedDaysAgo(2);

        // Act
        $orderCountByDateAfter = $this->getOrderCountByDate($dailyOrderCountChartPlugin);

        // Assert
        $today = $this->tester->getDateDaysAgo(0);
        $yesterday = $this->tester->getDateDaysAgo(1);
        $twoDaysAgo = $this->tester->getDateDaysAgo(2);
        $this->assertSame($orderCountByDateBefore[$today] + 2, $orderCountByDateAfter[$today]);
        $this->assertSame($orderCountByDateBefore[$yesterday], $orderCountByDateAfter[$yesterday]);
        $this->assertSame($orderCountByDateBefore[$twoDaysAgo] + 1, $orderCountByDateAfter[$twoDaysAgo]);
    }

    public function testGetChartDataIgnoresOrdersCreatedBeforeTheFirstDay(): void
    {
        // Arrange
        $dailyOrderCountChartPlugin = $this->createDailyOrderCountChartPluginShowingDays(3);
        $orderCountByDateBefore = $this->getOrderCountByDate($dailyOrderCountChartPlugin);
        $this->tester->haveOrderCreatedDaysAgo(3);

        // Act
        $orderCountByDateAfter = $this->getOrderCountByDate($dailyOrderCountChartPlugin);

        // Assert
        $this->assertSame($orderCountByDateBefore, $orderCountByDateAfter);
    }

    public function testGetChartDataShowsTodayOnlyWhenTheConfiguredDayCountIsNotPositive(): void
    {
        // Arrange
        $dailyOrderCountChartPlugin = $this->createDailyOrderCountChartPluginShowingDays(0);

        // Act
        $chartDataTransfer = $dailyOrderCountChartPlugin->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $this->assertSame([$this->tester->getDateDaysAgo(0)], $chartDataTransfer->getTraces()->offsetGet(0)->getLabels());
    }

    protected function createDailyOrderCountChartPluginShowingDays(int $days): DailyOrderCountChartPlugin
    {
        $dailyOrderCountChartPlugin = new DailyOrderCountChartPlugin();
        $dailyOrderCountChartPlugin->setConfig($this->tester->mockSalesStatisticsConfigMethod('getDailyOrderCountChartDays', $days));

        return $dailyOrderCountChartPlugin;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOrderCountByDate(DailyOrderCountChartPlugin $dailyOrderCountChartPlugin): array
    {
        $chartDataTransfer = $dailyOrderCountChartPlugin->getChartData(static::DATA_IDENTIFIER);

        return $this->tester->getValuesIndexedByLabel($chartDataTransfer->getTraces()->offsetGet(0));
    }
}
