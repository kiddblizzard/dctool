<?php
namespace App\Constants;
class ModelTypeOptions
{
    const SERVER = 'Server';
    const NETWORK_SWITCH = 'Network switch';
    const BLADE = 'Blade';
    const ENCLOSURE = 'Enclosure';

    private static $modelTypeOptions = [
        self::SERVER => 'SERVER',
        self::NETWORK_SWITCH => 'NETWORK_SWITCH',
        self::BLADE => 'BLADE',
        self::ENCLOSURE => 'ENCLOSURE',
    ];
    /**
     * Gets the indexed list of article options.
     *
     * @return array The indexed list of options
     */
    public static function getModelTypeOptions()
    {
        return self::$modelTypeOptions;
    }
}
