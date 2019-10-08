<?php namespace SnowReportKit\SnowReportKit;

class SRK
{

    /** @var string message level */
    public const EMERGENCY = 'emergency';
    public const ALERT     = 'alert';
    public const CRITICAL  = 'critical';
    public const ERROR     = 'error';
    public const WARNING   = 'warning';
    public const NOTICE    = 'notice';
    public const INFO      = 'info';
    public const DEBUG     = 'debug';

    /** @var string lift status */
    public const OPEN   = 'open';
    public const HOLD   = 'hold';
    public const CLOSED = 'closed';

    /** @var string lift types */
    public const CHAIR     = 'chairlift';
    public const CHAIRLIFT = 'chairlift';
    public const CARPET    = 'surface';
    public const SURFACE   = 'surface';
    public const TOW       = 'rope-tow';
    public const ROPETOW   = 'rope-tow';

    /** @var string grooming status */
    public const AM        = 'am';
    public const PM        = 'pm';
    public const UNGROOMED = 'ungroomed';

    /** @var string trail difficulty */
    public const GREEN       = 'green';
    public const BLUE        = 'blue';
    public const BLACK       = 'black';
    public const DOUBLE      = 'double';
    public const BACKCOUNTRY = 'backcountry';

    /** @var string trail type */
    public const DH           = 'alpine';
    public const DOWNHILL     = 'alpine';
    public const ALPINE       = 'alpine';
    public const XC           = 'cross-country';
    public const CROSSCOUNTRY = 'cross-country';

    /** @var string park difficulty */
    public const XS  = 'xs';
    public const S   = 's';
    public const M   = 'm';
    public const L   = 'l';
    public const XL  = 'xl';
    public const XXL = 'xxl';

    /** @var string units */
    public const IN = 'in';
    public const CM = 'cm';
    public const F  = 'f';
    public const C  = 'c';

}
