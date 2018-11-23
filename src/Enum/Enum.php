<?php
namespace LaravelCommons\Enum;

/**
 * Enum
 */
class Enum
{
    /**
     * @var int ID
     */
    public $id;

    /**
     * @var string 表示用ラベル
     */
    public $label;

    /**
     * 初期化コンストラクタ
     *
     * @param $id int ID
     * @param $label string ラベル
     */
    public function __construct($id, $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    /**
     * IDから値を取得する
     *
     * @param $id int ID
     * @return Enum Enumの下位クラス
     */
    public static function valueOf($id)
    {
        foreach (static::$Values as $value) {
            if ($value->id == $id) {
                return $value;
            }
        }
        return null;
    }

    public function __toString()
    {
        return $this->label;
    }
}
