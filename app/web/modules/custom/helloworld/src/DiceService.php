<?php

namespace Drupal\helloworld;

/**
 * DiceService クラス。
 *
 * このクラスはサイコロの機能を提供するためのもので、
 * DrupalのHelloWorldモジュール内で使用されます。
 */
class DiceService {

    /**
     * サイコロを振るメソッド。
     *
     * このメソッドは1から6の間でランダムな数値を生成し、
     * それをサイコロの出目として返します。
     *
     * @return int
     *   サイコロの出目を表すランダムな数値（1〜6のいずれか）。
     */
    public function rollDice() {
        return rand(1, 6);
    }
}