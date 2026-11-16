<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Zed\SalesStatistics\Communication\Plugin\OrderItemCountByStatusChartPlugin;
use SprykerTest\Zed\SalesStatistics\SalesStatisticsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesStatistics
 * @group Communication
 * @group Plugin
 * @group OrderItemCountByStatusChartPluginTest
 * Add your own group annotations below this line
 */
class OrderItemCountByStatusChartPluginTest extends Unit
{
    protected const string DATA_IDENTIFIER = 'order-item-count-by-status';

    protected const string STATE_WITH_MORE_ITEMS = 'sales-statistics-test-state-with-more-items';

    protected const string STATE_WITH_FEWER_ITEMS = 'sales-statistics-test-state-with-fewer-items';

    protected SalesStatisticsCommunicationTester $tester;

    public function testGetChartDataCountsEveryOrderItemInItsState(): void
    {
        // Arrange
        $this->tester->haveOrderWithItemsInState(2, static::STATE_WITH_MORE_ITEMS);
        $this->tester->haveOrderWithItemsInState(1, static::STATE_WITH_MORE_ITEMS);
        $this->tester->haveOrderWithItemsInState(1, static::STATE_WITH_FEWER_ITEMS);

        // Act
        $chartDataTransfer = (new OrderItemCountByStatusChartPlugin())->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $itemCountByState = $this->tester->getValuesIndexedByLabel($chartDataTransfer->getTraces()->offsetGet(0));
        $this->assertSame(3, $itemCountByState[static::STATE_WITH_MORE_ITEMS]);
        $this->assertSame(1, $itemCountByState[static::STATE_WITH_FEWER_ITEMS]);
    }

    public function testGetChartDataListsStatesWithMoreItemsFirst(): void
    {
        // Arrange
        $this->tester->haveOrderWithItemsInState(1, static::STATE_WITH_FEWER_ITEMS);
        $this->tester->haveOrderWithItemsInState(3, static::STATE_WITH_MORE_ITEMS);

        // Act
        $chartDataTransfer = (new OrderItemCountByStatusChartPlugin())->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $labels = $chartDataTransfer->getTraces()->offsetGet(0)->getLabels();
        $this->assertLessThan(
            array_search(static::STATE_WITH_FEWER_ITEMS, $labels, true),
            array_search(static::STATE_WITH_MORE_ITEMS, $labels, true),
        );
    }

    public function testGetChartDataDescribesTheOrderStatusChart(): void
    {
        // Act
        $chartDataTransfer = (new OrderItemCountByStatusChartPlugin())->getChartData(static::DATA_IDENTIFIER);

        // Assert
        $this->assertSame(static::DATA_IDENTIFIER, $chartDataTransfer->getKey());
        $this->assertSame(OrderItemCountByStatusChartPlugin::TITLE, $chartDataTransfer->getTitle());
        $this->assertSame('order-status', $chartDataTransfer->getView());
        $this->assertCount(1, $chartDataTransfer->getTraces());
        $this->assertSame(ChartConfig::CHART_TYPE_PIE, $chartDataTransfer->getTraces()->offsetGet(0)->getType());
    }
}
