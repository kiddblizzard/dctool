<?php
namespace App\Constants;
class DeviceStatusOptions
{
    const IN_DEPOSITORY = 'In Depository';
    const RUNNING = 'Running';
    const ISOLATED = 'Isolated';
    const DECOMMISSIONED = 'Decommissioned';

    private static $options = [
        self::IN_DEPOSITORY => 'in_depository',
        self::RUNNING => 'running',
        self::ISOLATED => 'isolated',
        self::DECOMMISSIONED => 'decommissioned',
    ];
    /**
     * Gets the indexed list of BAU status options.
     *
     * @return array The indexed list of options
     */
    public static function getOptions()
    {
        return self::$options;
    }

    public static function getText($option)
    {
        foreach (self::$options as $key => $val) {
            if ($val == $option) {
                return $key;
            }
        }
    }
}
