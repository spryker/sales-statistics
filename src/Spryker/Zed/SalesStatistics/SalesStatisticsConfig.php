<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesStatisticsConfig extends AbstractBundleConfig
{
    protected const int DAILY_ORDER_COUNT_CHART_DAYS = 7;

    /**
     * Specification:
     * - Returns the number of calendar days, today included, that `DailyOrderCountChartPlugin` shows.
     *
     * @api
     */
    public function getDailyOrderCountChartDays(): int
    {
        return static::DAILY_ORDER_COUNT_CHART_DAYS;
    }
}
