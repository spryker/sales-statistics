<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business\Reader;

use Generated\Shared\Transfer\ChartDataTraceTransfer;

interface ReaderInterface
{
    public function getOrderCountStatisticByDays(int $days): ChartDataTraceTransfer;

    public function getStatusOrderStatistic(): ChartDataTraceTransfer;

    public function getTopOrderStatistic(int $countProduct): ChartDataTraceTransfer;
}
