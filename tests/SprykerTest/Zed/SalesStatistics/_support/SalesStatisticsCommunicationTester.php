<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics;

use Codeception\Actor;
use DateTime;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\SalesStatistics\SalesStatisticsConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesStatisticsCommunicationTester extends Actor
{
    use _generated\SalesStatisticsCommunicationTesterActions;

    public function haveOrderCreatedDaysAgo(int $daysAgo): SpySalesOrder
    {
        $salesOrderEntity = $this->haveSalesOrderEntity([(new ItemBuilder())->build()]);
        $salesOrderEntity->setCreatedAt(new DateTime(sprintf('-%d days', $daysAgo)));
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    public function haveOrderWithItemsInState(int $itemCount, string $omsStateName): SpySalesOrder
    {
        $itemTransfers = [];

        for ($itemIndex = 0; $itemIndex < $itemCount; $itemIndex++) {
            $itemTransfers[] = (new ItemBuilder())->build();
        }

        return $this->haveSalesOrderEntity($itemTransfers, [], $omsStateName);
    }

    public function haveOrderWithItemNamed(string $itemName): SpySalesOrder
    {
        return $this->haveSalesOrderEntity([
            (new ItemBuilder([ItemTransfer::NAME => $itemName]))->build(),
        ]);
    }

    public function mockSalesStatisticsConfigMethod(string $methodName, int $return): SalesStatisticsConfig
    {
        /** @var \Spryker\Zed\SalesStatistics\SalesStatisticsConfig $salesStatisticsConfig */
        $salesStatisticsConfig = $this->mockConfigMethod($methodName, $return);

        return $salesStatisticsConfig;
    }

    public function getDateDaysAgo(int $daysAgo): string
    {
        return date('Y-m-d', (int)strtotime(sprintf('-%d days', $daysAgo)));
    }

    /**
     * @return array<string, mixed>
     */
    public function getValuesIndexedByLabel(ChartDataTraceTransfer $chartDataTraceTransfer): array
    {
        $values = $chartDataTraceTransfer->getValues();
        $valuesByLabel = [];

        foreach ($chartDataTraceTransfer->getLabels() as $index => $label) {
            $valuesByLabel[$label] = $values[$index];
        }

        return $valuesByLabel;
    }
}
