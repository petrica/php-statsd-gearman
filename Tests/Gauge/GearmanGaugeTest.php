<?php
/**
 * Created by PhpStorm.
 * User: Petrica
 * Date: 3/31/2016
 * Time: 1:15
 */
namespace Petrica\StatsdGearman\Tests;

use Petrica\StatsdSystem\Collection\ValuesCollection;

class GearmanGaugeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetGauge()
    {
        $manager = $this->getMockBuilder('Net_Gearman_Manager')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'status'
            ))
            ->getMock();

        $manager->expects($this->atLeastOnce())
            ->method('status')
            ->willReturnOnConsecutiveCalls(
                false,
                array(
                    'one' => array(
                        'in_queue' => 1,
                        'jobs_running' => 2,
                        'capable_workers' => 4
                    )
                ),
                array(
                    'two' => array(
                        'jobs_running' => 1
                    )
                ),
                array(
                    'three' => array(
                        'in_queue' => 1
                    )
                ),
                array(
                    'four' => array(
                        'capable_workers' => 1
                    )
                )
            );

        $gauge = $this->getMockBuilder('Petrica\StatsdGearman\Gauge\GearmanGauge')
            ->setConstructorArgs(array(
                'localhost:4730',
                1
            ))
            ->setMethods(array(
                'getManager'
            ))
            ->getMock();

        $gauge->expects($this->atLeastOnce())
            ->method('getManager')
            ->willReturn($manager);

        // Test if attributes have been properly setup
        $this->assertAttributeEquals('localhost:4730', 'server', $gauge);
        $this->assertAttributeEquals(1, 'timeout', $gauge);

        // Test empty collection
        $collection = $gauge->getCollection();
        $emptyCollection = new ValuesCollection();
        $this->assertEquals($emptyCollection, $collection);

        // Test complete collection
        $collection = $gauge->getCollection();
        $filledCollection = new ValuesCollection(array(
            'one.queue' => 1,
            'one.running' => 2,
            'one.workers' => 4
        ));
        $this->assertEquals($filledCollection, $collection);

        // Test only jobs running
        $collection = $gauge->getCollection();
        $filledCollection = new ValuesCollection(array(
            'two.queue' => null,
            'two.running' => 1,
            'two.workers' => null
        ));
        $this->assertEquals($filledCollection, $collection);

        // Test only queue running
        $collection = $gauge->getCollection();
        $filledCollection = new ValuesCollection(array(
            'three.queue' => 1,
            'three.running' => null,
            'three.workers' => null
        ));
        $this->assertEquals($filledCollection, $collection);

        // Test only workers running
        $collection = $gauge->getCollection();
        $filledCollection = new ValuesCollection(array(
            'four.queue' => null,
            'four.running' => null,
            'four.workers' => 1
        ));
        $this->assertEquals($filledCollection, $collection);
    }
}