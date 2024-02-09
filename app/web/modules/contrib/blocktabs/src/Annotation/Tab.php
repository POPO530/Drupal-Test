<?php

namespace Drupal\blocktabs\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a tab annotation object.
 *
 * Plugin Namespace: Plugin\Tab.
 *
 * For a working example, see
 * \Drupal\blocktabs\Plugin\Tab\BlockTab
 *
 * @see hook_tab_info_alter()
 * @see \Drupal\blocktabs\ConfigurableTabInterface
 * @see \Drupal\blocktabs\ConfigurableTabBase
 * @see \Drupal\blocktabs\TabInterface
 * @see \Drupal\blocktabs\TabBase
 * @see \Drupal\blocktabs\TabManager
 * @see plugin_api
 *
 * @Annotation
 */
class Tab extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the tab.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */

  public $label;

  /**
   * A brief description of the tab.
   *
   * This will be shown when adding or configuring this tab.
   *
   * @var \Drupal\Core\Annotation\Translationoptional
   * @ingroup plugin_translatable
   */
  public $description = '';

}
