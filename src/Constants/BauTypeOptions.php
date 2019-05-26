<?php
namespace App\Constants;
class BauTypeOptions
{
    const BREAKFIX = 'Breakfix';
    const DCIM_REQUEST = 'DCIM Request';
    const COODINATE = 'Coodinate';
    const RACKSTACK = 'Rackstack';
    const POWER_ON = 'Power On';
    const DECOMMISSION = 'Decommission';
    const CABLING = 'Cabling';
    const FACILITY = 'Facility';

    private static $bauTypeOptions = [
        self::BREAKFIX => 'breakfix',
        self::DCIM_REQUEST => 'dcim_request',
        self::COODINATE => 'coodinate',
        self::RACKSTACK => 'rackstack',
        self::POWER_ON => 'power_on',
        self::DECOMMISSION => 'decommission',
        self::CABLING => 'cabling',
        self::FACILITY => 'facility',
    ];
    /**
     * Gets the indexed list of BAU options.
     *
     * @return array The indexed list of options
     */
    public static function getBauTypeOptions()
    {
        return self::$bauTypeOptions;
    }
}
