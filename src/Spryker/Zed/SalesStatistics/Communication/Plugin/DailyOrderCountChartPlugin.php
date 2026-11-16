<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Communication\Plugin;

use Generated\Shared\Transfer\ChartDataTransfer;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Shared\Dashboard\Dependency\Plugin\DashboardPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesStatistics\Communication\SalesStatisticsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesStatistics\SalesStatisticsConfig getConfig()
 */
class DailyOrderCountChartPlugin extends AbstractPlugin implements ChartPluginInterface, DashboardPluginInterface
{
    /**
     * @api
     */
    public const string NAME = 'daily-order-count';

    /**
     * @api
     */
    public const string TITLE = 'Count orders';

    protected const string CHART_VIEW = 'daily-bar';

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     * - Returns one bar per calendar day for the days set in `SalesStatisticsConfig::getDailyOrderCountChartDays()`, today included.
     * - Counts the orders created on each day, starting at midnight of the first day.
     * - Days without orders are returned with a count of zero.
     *
     * @api
     *
     * @param string|null $dataIdentifier
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $chartDataTraceTransfer = $this->getBusinessFactory()->createReader()->getDailyOrderCountStatistic(
            $this->getConfig()->getDailyOrderCountChartDays(),
        );
        $chartDataTraceTransfer->setType(ChartConfig::CHART_TYPE_BAR);

        return (new ChartDataTransfer())
            ->addTrace($chartDataTraceTransfer)
            ->setKey($dataIdentifier)
            ->setTitle(static::TITLE)
            ->setView(static::CHART_VIEW);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function render(): string
    {
        return $this->getFactory()
            ->getTwigEnvironment()
            ->createTemplate(
                sprintf("{{ chart('%s','%s') }}", static::NAME, static::NAME),
            )
            ->render([]);
    }
}
