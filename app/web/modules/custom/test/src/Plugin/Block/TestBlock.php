<?php

// Drupalの名前空間と必要な基底クラスを使用
namespace Drupal\test\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * 'MyModuleBlock'ブロックを提供します。
 *
 * @Block(
 *   id = "my_module_block",
 *   admin_label = @Translation("My Module Block"),
 * )
 */
class TestBlock extends BlockBase {

  /**
   * ブロックの内容を構築します。
   *
   * @return array
   *   ブロックのレンダリング配列を返します。
   */
  public function build() {
    return [
      // 'test_block_template'テーマを使用してブロックをレンダリング
      '#theme' => 'test_block_template',
      // カスタムJavaScriptライブラリ（'test/ajax_behavior'）をページに添付
      '#attached' => [
        'library' => [
          'test/ajax_behavior',
        ],
      ],
    ];
  }

}