<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesStatistics\Business\Reader\Reader;
use Spryker\Zed\SalesStatistics\Business\Reader\ReaderInterface;

/**
 * @method \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesStatistics\SalesStatisticsConfig getConfig()
 */
class SalesStatisticsBusinessFactory extends AbstractBusinessFactory
{
    public function createReader(): ReaderInterface
    {
        return new Reader($this->getRepository());
    }
}
