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
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesStatistics\SalesStatisticsConfig getConfig()
 */
class TopOrdersChartPlugin extends AbstractPlugin implements ChartPluginInterface, DashboardPluginInterface
{
    /**
     * @var int
     */
    public const COUNT_PRODUCT = 10;

    /**
     * @var string
     */
    public const NAME = 'top-orders';

    /**
     * @var string
     */
    public const TITLE = 'Top Orders';

    /**
     * @var array<string, string>
     */
    public const OPTIONS = [
        'orientation' => 'h',
    ];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
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
     *
     * @return \Generated\Shared\Transfer\ChartDataTransfer
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $data = new ChartDataTransfer();
        $data->addTrace(
            $this->getFacade()->getTopOrderStatistic(static::COUNT_PRODUCT)
                ->addOption(static::OPTIONS)
                ->setType(ChartConfig::CHART_TYPE_BAR),
        );
        $data->setKey($dataIdentifier);
        $data->setTitle(static::TITLE);

        return $data;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
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
