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
class OrderItemCountByStatusChartPlugin extends AbstractPlugin implements ChartPluginInterface, DashboardPluginInterface
{
    /**
     * @api
     */
    public const string NAME = 'order-item-count-by-status';

    /**
     * @api
     */
    public const string TITLE = 'Order items by status';

    protected const string CHART_VIEW = 'order-status';

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
     *
     * @api
     *
     * @param string|null $dataIdentifier
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $chartDataTraceTransfer = $this->getBusinessFactory()->createReader()->getOrderItemCountByStatusStatistic();
        $chartDataTraceTransfer->setType(ChartConfig::CHART_TYPE_PIE);

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
