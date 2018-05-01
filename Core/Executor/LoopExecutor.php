<?php

namespace Kaliop\eZLoremIpsumBundle\Core\Executor;

use Kaliop\eZMigrationBundle\Core\Executor\AbstractExecutor;
use Kaliop\eZMigrationBundle\API\Value\MigrationStep;
use Kaliop\eZMigrationBundle\Core\Executor\IgnorableStepExecutorTrait;
use Kaliop\eZMigrationBundle\Core\MigrationService;

class LoopExecutor extends AbstractExecutor
{
    use IgnorableStepExecutorTrait;

    protected $supportedStepTypes = array('loop');

    /** @var MigrationService $migrationService */
    protected $migrationService;

    public function __construct($migrationService)
    {
        $this->migrationService = $migrationService;
    }

    /**
     * @param MigrationStep $step
     * @return mixed
     * @throws \Exception
     */
    public function execute(MigrationStep $step)
    {
        parent::execute($step);

        if (!isset($step->dsl['repeat']) || $step->dsl['repeat'] < 0) {
            throw new \Exception("Invalid step definition: missing 'repeat' or not a positive integer");
        }

        if (!isset($step->dsl['step']) || !is_array($step->dsl['step'])) {
            throw new \Exception("Invalid step definition: missing 'step' or not an array");
        }

        // no need for a 'mode' for now
        /*$action = $step->dsl['mode'];

        if (!in_array($action, $this->supportedActions)) {
            throw new \Exception("Invalid step definition: value '$action' is not allowed for 'mode'");
        }*/

        $this->skipStepIfNeeded($step);

        $stepDef = $step->dsl['step'];

        $type = $stepDef['type'];
        try {
            $stepExecutor = $this->migrationService->getExecutor($type);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage() . " in sub-step of a loop step");
        }

        unset($stepDef['type']);
        $result = null;

        // NB: we are *not* firing events for each pass of the loop... it might be worth making that optionally happen ?
        for ($i = 0; $i < $step->dsl['repeat']; $i++) {
            $subStep = new MigrationStep($type, $stepDef, array_merge($step->context, array()));

            $result = $stepExecutor->execute($subStep);
        }

        return $result;
    }

}
