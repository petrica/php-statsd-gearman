<?php
/**
 * Created by PhpStorm.
 * User: Petrica
 * Date: 3/29/2016
 * Time: 0:13
 */
namespace Petrica\StatsdGearman\Gauges;

use Petrica\StatsdSystem\Gauge\GaugeInterface;

abstract class AbstractGearman implements GaugeInterface
{
    /**
     * @var \Net_Gearman_Manager
     */
    private $manager;

    /**
     * Gearman server connection string as localhost:7003
     *
     * @var string
     */
    private $server;

    /**
     * Connection timeout in seconds
     *
     * @var string
     */
    private $timeout;

    /**
     * AbstractGearman constructor.
     * @param $server
     * @param int $timeout
     */
    public function __construct($server, $timeout = 1)
    {
        $this->server = $server;
        $this->timeout = $timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function getSamplingPeriod()
    {
        return 2;
    }

    /**
     * Connect to gearman server and return an array with job statuses
     */
    public function getStats()
    {

    }

    /**
     * Connect to gearman server
     *
     * @return \Net_Gearman_Manager
     */
    protected function getManager()
    {
        return new \Net_Gearman_Manager($this->server, $this->timeout);
    }
}