<?php
/**
 * Created by PhpStorm.
 * User: Petrica
 * Date: 3/29/2016
 * Time: 0:13
 */
namespace Petrica\StatsdGearman\Gauge;

use Petrica\StatsdSystem\Collection\ValuesCollection;
use Petrica\StatsdSystem\Gauge\GaugeInterface;

class GearmanGauge implements GaugeInterface
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
     * {@inheritdoc}
     */
    public function getCollection()
    {
        $collection = new ValuesCollection();

        $stats = $this->getStats();

        if (!empty($stats)) {
            foreach ($stats as $jobName => $data) {
                $collection->add($jobName . '.queue.count', isset($data['in_queue']) ? $data['in_queue'] : null);
                $collection->add($jobName . '.running.count', isset($data['jobs_running']) ? $data['jobs_running'] : null);
                $collection->add($jobName . '.workers.count', isset($data['capable_workers']) ? $data['capable_workers'] : null);
            }
        }

        return $collection;
    }

    /**
     * Connect to gearman server and return an array with job statuses
     *
     * /**
     * will return:
     * [ 'jobName' => [
     *      'in_queue' => 0,
     *      'jobs_running => 0,
     *      'capable_workers => 0
     * ]]
     *
     * @return mixed
     */
    protected function getStats()
    {
        $manager = $this->getManager();

        return ($manager) ? $manager->status() : false;
    }

    /**
     * Connect to gearman server
     *
     * @return \Net_Gearman_Manager
     */
    protected function getManager()
    {
        if (null === $this->manager) {
            try {
                $this->manager = new \Net_Gearman_Manager($this->server, $this->timeout);
            }
            catch (\Net_Gearman_Exception $e) {
                /**
                 * Fail silently
                 *
                 * TODO: implement error catcher
                 */
            }
        }

        return $this->manager;
    }
}