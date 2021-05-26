<?php
namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Symfony\Component\Workflow\WorkflowInterface;

class PlanStatus extends Type
{
    const ENUM_PLAN_STATUS = 'plan_status';
        
    const PLAN_STATUS_UNDER_CONSTRUCTION = 'under_construction';
    const PLAN_STATUS_LOCKED = 'locked';
    const PLAN_STATUS_EVENT_FILLING_STARTED = 'event_filling_started';
    const PLAN_STATUS_EVENT_FILLING_FINISHED = 'event_filling_finished';
    const PLAN_STATUS_MAP_ID_FILLING_STARTED = 'map_id_filling_started';
    const PLAN_STATUS_MAP_ID_FILLING_FINISHED = 'map_id_filling_finished';
    const PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED = 'normalized_data_generation_started';
    const PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED = 'normalized_data_generation_finished';
    const PLAN_STATUS_SCHEDULE_CALCULATION_STARTED = 'schedule_calculation_started';
    const PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED = 'schedule_calculation_finished';
    const PLAN_STATUS_NORMALISATION_ERROR = 'normalisation_error';
    const PLAN_STATUS_CALCULATION_ERROR = 'calculation_error';

    private function getPlanStatusList() : array
    {
        return [
            self::PLAN_STATUS_UNDER_CONSTRUCTION,
            self::PLAN_STATUS_LOCKED,
            self::PLAN_STATUS_EVENT_FILLING_STARTED ,
            self::PLAN_STATUS_EVENT_FILLING_FINISHED,
            self::PLAN_STATUS_MAP_ID_FILLING_STARTED,
            self::PLAN_STATUS_MAP_ID_FILLING_FINISHED,
            self::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED,
            self::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED,
            self::PLAN_STATUS_SCHEDULE_CALCULATION_STARTED,
            self::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED,
            self::PLAN_STATUS_NORMALISATION_ERROR,
            self::PLAN_STATUS_CALCULATION_ERROR,
        ];
    }
    
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string
    {
        return substr(
            array_reduce(
                $this->getPlanStatusList(),
                fn(string $carry, string $item) => $carry . "'" . $item . "', ",
                "ENUM("
            ),
            0,
            -2
        ) . ")";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->getPlanStatusList())) {
            throw new \InvalidArgumentException("Invalid status");
        }
        return $value;
    }

    public function getName()
    {
        return self::ENUM_PLAN_STATUS;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}