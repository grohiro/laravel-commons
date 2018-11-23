<?php
namespace LaravelCommons\Tests\Enum;

use LaravelCommons\Enum\Enum;
use LaravelCommons\Enum\WithCode;

/**
 * Platform
 */
class Platform extends Enum
{
    use WithCode;

    public static $Ios;
    public static $Android;
    public static $Values;

    public function __construct($id, $label, $code)
    {
        parent::__construct($id, $label);
        $this->code = $code;
    }

    public static function init() {

        self::$Ios = new Platform(1, 'iOS', 'ios');
        self::$Android = new Platform(2, 'Android', 'android');

        self::$Values = [
            self::$Ios,
            self::$Android,
        ];
    }
}
