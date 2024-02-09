<?php

namespace Drupal\blocktabs;

use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines the interface for configurable tab.
 *
 * @see \Drupal\blocktabs\Annotation\Tab
 * @see \Drupal\blocktabs\ConfigurableTabBase
 * @see \Drupal\blocktabs\TabInterface
 * @see \Drupal\blocktabs\TabBase
 * @see \Drupal\blocktabs\TabManager
 * @see plugin_api
 */
interface ConfigurableTabInterface extends TabInterface, PluginFormInterface {

  /**
   * Return CSS ID for the tab.
   *
   * @return string|null
   *   CSS ID or NULL if not set.
   */
  public function getTabCssId();

}
