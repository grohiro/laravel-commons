<?php
namespace LaravelCommons\Enum;

/**
 * Enumにコード値を追加する
 */
trait WithCode
{
    public $code;

    /**
     * コードから値を取得する
     *
     * @param $code string コード
     */
    public static function valueOfCode($code)
    {
        foreach (self::$Values as $value) {
            if ($value->code == $code) {
                return $value;
            }
        }
        return null;
    }
}
