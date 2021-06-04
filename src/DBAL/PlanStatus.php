<?php

namespace App\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PlanStatus extends Type
{
    public const ENUM_PLAN_STATUS = 'plan_status';

    public const PLAN_STATUS_UNDER_CONSTRUCTION = 'under_construction';
    public const PLAN_STATUS_LOCKED = 'locked';
    public const PLAN_STATUS_EVENT_FILLING_STARTED = 'event_filling_started';
    public const PLAN_STATUS_EVENT_FILLING_FINISHED = 'event_filling_finished';
    public const PLAN_STATUS_MAP_ID_FILLING_STARTED = 'map_id_filling_started';
    public const PLAN_STATUS_MAP_ID_FILLING_FINISHED = 'map_id_filling_finished';
    public const PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED = 'normalized_data_generation_started';
    public const PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED = 'normalized_data_generation_finished';
    public const PLAN_STATUS_CALCULATION_STARTED = 'calculation_started';
    public const PLAN_STATUS_CALCULATION_FINISHED = 'calculation_finished';
    public const PLAN_STATUS_CALCULATION_UNSUCCESSFUL = 'calculation_unsuccessful';
    public const PLAN_STATUS_RESULT_IMPORT_STARTED = 'result_import_started';
    public const PLAN_STATUS_RESULT_IMPORT_FINISHED = 'result_import_finished';
    public const PLAN_STATUS_NORMALISATION_ERROR = 'normalisation_error';
    public const PLAN_STATUS_CALCULATION_ERROR = 'calculation_error';
    public const PLAN_STATUS_RESULT_IMPORT_ERROR = 'result_import_error';

    private function getPlanStatusList(): array
    {
        return [
            self::PLAN_STATUS_UNDER_CONSTRUCTION,
            self::PLAN_STATUS_LOCKED,
            self::PLAN_STATUS_EVENT_FILLING_STARTED,
            self::PLAN_STATUS_EVENT_FILLING_FINISHED,
            self::PLAN_STATUS_MAP_ID_FILLING_STARTED,
            self::PLAN_STATUS_MAP_ID_FILLING_FINISHED,
            self::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED,
            self::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED,
            self::PLAN_STATUS_CALCULATION_STARTED,
            self::PLAN_STATUS_CALCULATION_FINISHED,
            self::PLAN_STATUS_CALCULATION_UNSUCCESSFUL,
            self::PLAN_STATUS_RESULT_IMPORT_STARTED,
            self::PLAN_STATUS_RESULT_IMPORT_FINISHED,
            self::PLAN_STATUS_NORMALISATION_ERROR,
            self::PLAN_STATUS_CALCULATION_ERROR,
            self::PLAN_STATUS_RESULT_IMPORT_ERROR,
        ];
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return substr(
            array_reduce(
                $this->getPlanStatusList(),
                fn (string $carry, string $item) => $carry."'".$item."', ",
                'ENUM('
            ),
            0,
            -2
        ).')';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->getPlanStatusList())) {
            throw new \InvalidArgumentException('Invalid status');
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
