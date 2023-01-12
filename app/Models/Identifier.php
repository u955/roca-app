<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Identifier extends Model
{
    // -> 使用する文字
    private static $source = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    // 識別子の生成
    private static function generate($length) {
        return substr(str_shuffle(str_repeat(self::$source, $length)), 0, $length);
    }

    // 識別子の検証
    private static function checkUnique($table, $column, $id) {
        $response = DB::table($table)->where($column, $id)->value($column);
        return is_null($response);
    }

    // Tokenの生成
    public static function generateToken($table, $column) {
        $token = self::generate(15); // -> 15文字長
        $is_unique = self::checkUnique($table, $column, $token);
        return ($is_unique) ? $token : self::generateToken($table, $column);
    }
}
