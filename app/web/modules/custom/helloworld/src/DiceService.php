<?php

namespace Drupal\helloworld;

/**
 * サイコロを振るための DiceService クラス。
 */
class DiceService {

    /**
     * サイコロを振るメソッド。
     *
     * このメソッドは1から6のランダムな数値を返します。
     *
     * @return int
     *   サイコロの出目を表すランダムな数値（1〜6のいずれか）。
     */
    public function rollDice() {
        return rand(1, 6);
    }
}