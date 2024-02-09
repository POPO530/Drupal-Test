<?php

namespace Drupal\blocktabs;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a base class for configurable tab.
 *
 * @see \Drupal\blocktabs\Annotation\Tab
 * @see \Drupal\blocktabs\ConfigurableTabInterface
 * @see \Drupal\blocktabs\TabInterface
 * @see \Drupal\blocktabs\TabBase
 * @see \Drupal\blocktabs\TabManager
 * @see plugin_api
 */
abstract class ConfigurableTabBase extends TabBase implements ConfigurableTabInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['tab_css_id' => NULL]
      + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['tab_css_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tab CSS id'),
      '#description' => $this->t("Leave empty if you don't want to change default."),
      '#default_value' => $this->configuration['tab_css_id'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    $tab_css_id = $form_state->getValue('tab_css_id');
    if ($tab_css_id && $tab_css_id != Html::getId($tab_css_id)) {
      $form_state->setError($form['data']['tab_css_id'], $this->t('The CSS id is not valid. Suggest using this #id: %suggestion', ['%suggestion' => Html::getId($tab_css_id)]));
    } 
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['tab_css_id'] = $form_state->getValue('tab_css_id');
  }

  /**
   * {@inheritdoc}
   */
  public function getTabCssId() {
    return NestedArray::getValue($this->configuration, ['tab_css_id']);
  }

}
