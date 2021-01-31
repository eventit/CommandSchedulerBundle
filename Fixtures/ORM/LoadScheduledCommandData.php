<?php

namespace JMose\CommandSchedulerBundle\Fixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;

/**
 * Class LoadScheduledCommandData.
 *
 * @author  Julien Guyon <julienguyon@hotmail.com>
 */
class LoadScheduledCommandData implements FixtureInterface
{
    protected ?ObjectManager $manager = null;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $now = new \DateTime();
        $today = clone $now;
        $beforeYesterday = $now->modify('-2 days');

        $this->createScheduledCommand('one', 'debug:container', '--help', '@daily', 'one.log', 100, $beforeYesterday);
        $this->createScheduledCommand('two', 'debug:container', '', '@daily', 'two.log', 80, $beforeYesterday, true);
        $this->createScheduledCommand('three', 'debug:container', '', '@daily', 'three.log', 60, $today, false, true);
        $this->createScheduledCommand('four', 'debug:router', '', '@daily', 'four.log', 40, $today, false, false, true, -1);
    }

    /**
     * Create a new ScheduledCommand in database.
     *
     * @param string        $name
     * @param string        $command
     * @param string        $arguments
     * @param string        $cronExpression
     * @param string        $logFile
     * @param int           $priority
     * @param \DateTime|null $lastExecution
     * @param bool          $locked
     * @param bool          $disabled
     * @param bool          $executeNow
     * @param int|null      $lastReturnCode
     */
    protected function createScheduledCommand(
        string $name, string $command, string $arguments, string $cronExpression,
        string $logFile, int $priority = 0, ?\DateTime $lastExecution = null,
        bool $locked = false, bool $disabled = false, bool $executeNow = false,
        ?int $lastReturnCode = null): bool
    {
        $scheduledCommand = new ScheduledCommand();
        $scheduledCommand
            ->setName($name)
            ->setCommand($command)
            ->setArguments($arguments)
            ->setCronExpression($cronExpression)
            ->setLogFile($logFile)
            ->setPriority($priority)
            ->setLastExecution($lastExecution)
            ->setLocked($locked)
            ->setDisabled($disabled)
            ->setLastReturnCode($lastReturnCode)
            ->setExecuteImmediately($executeNow);

        $this->manager->persist($scheduledCommand);
        $this->manager->flush();

        return true;
    }
}
