<?php
namespace App\Constants;
class BauStatusOptions
{
    const PENDING = 'Pending';
    const DEPLOYMENT = 'Deployment';
    const COMPLETE = 'Complete';
    const RESCHEDULED = 'Rescheduled';
    const CANCELLED = 'Cancelled';

    private static $options = [
        self::PENDING => 'pending',
        self::DEPLOYMENT => 'deployment',
        self::COMPLETE => 'complete',
        self::RESCHEDULED => 'rescheduled',
        self::CANCELLED => 'cancelled',
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
