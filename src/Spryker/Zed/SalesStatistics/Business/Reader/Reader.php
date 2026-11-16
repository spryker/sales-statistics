<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business\Reader;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface;

class Reader implements ReaderInterface
{
    protected const string DATE_FORMAT = 'Y-m-d';

    protected const string DAY_START_TIME = '00:00:00';

    /**
     * @var \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface
     */
    protected $repository;

    public function __construct(SalesStatisticsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getOrderCountStatisticByDays(int $days): ChartDataTraceTransfer
    {
        return $this->repository->getOrderCountStatisticByDays($days);
    }

    public function getDailyOrderCountStatistic(int $days): ChartDataTraceTransfer
    {
        $dates = $this->getDatesEndingToday(max(1, $days));
        $chartDataTraceTransfer = $this->repository->getOrderCountStatisticFromDate(
            sprintf('%s %s', $dates[0], static::DAY_START_TIME),
        );

        return $this->expandWithDaysWithoutOrders($chartDataTraceTransfer, $dates);
    }

    public function getStatusOrderStatistic(): ChartDataTraceTransfer
    {
        return $this->repository->getStatusOrderStatistic();
    }

    public function getOrderItemCountByStatusStatistic(): ChartDataTraceTransfer
    {
        return $this->repository->getOrderItemCountByStatusStatistic();
    }

    public function getTopOrderStatistic(int $countProduct): ChartDataTraceTransfer
    {
        return $this->repository->getTopOrderStatistic($countProduct);
    }

    /**
     * @return list<string>
     */
    protected function getDatesEndingToday(int $days): array
    {
        $dates = [];

        for ($daysAgo = $days - 1; $daysAgo >= 0; $daysAgo--) {
            $dates[] = date(static::DATE_FORMAT, (int)strtotime(sprintf('-%d days', $daysAgo)));
        }

        return $dates;
    }

    /**
     * @param list<string> $dates
     */
    protected function expandWithDaysWithoutOrders(ChartDataTraceTransfer $chartDataTraceTransfer, array $dates): ChartDataTraceTransfer
    {
        $orderCountByDate = $this->indexValuesByLabel($chartDataTraceTransfer);
        $dailyChartDataTraceTransfer = new ChartDataTraceTransfer();

        foreach ($dates as $date) {
            $dailyChartDataTraceTransfer->addLabel($date);
            $dailyChartDataTraceTransfer->addValue($orderCountByDate[$date] ?? 0);
        }

        return $dailyChartDataTraceTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    protected function indexValuesByLabel(ChartDataTraceTransfer $chartDataTraceTransfer): array
    {
        $values = $chartDataTraceTransfer->getValues();
        $valuesByLabel = [];

        foreach ($chartDataTraceTransfer->getLabels() as $index => $label) {
            $valuesByLabel[$label] = $values[$index] ?? 0;
        }

        return $valuesByLabel;
    }
}
