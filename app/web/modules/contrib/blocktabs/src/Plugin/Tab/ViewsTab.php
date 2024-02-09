<?php

namespace Drupal\blocktabs\Plugin\Tab;

use Drupal\Core\Form\FormStateInterface;
use Drupal\blocktabs\ConfigurableTabBase;
use Drupal\blocktabs\BlocktabsInterface;
use Drupal\views\Views;

/**
 * Views tab.
 *
 * @Tab(
 *   id = "views_tab",
 *   label = @Translation("views tab"),
 *   description = @Translation("views tab.")
 * )
 */
class ViewsTab extends ConfigurableTabBase {

  /**
   * {@inheritdoc}
   */
  public function addTab(BlocktabsInterface $blocktabs) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    $markup = $this->t('view name:') . $this->configuration['view_name'] . '; ';
    $markup .= $this->t('display:') . $this->configuration['view_display'] . '; ';
    if (!empty($this->configuration['view_arg'])) {
      $markup .= $this->t('argument:') . $this->configuration['view_arg'] . '; ';
    }
    $markup .= $this->t('check access: @check_access', ['@check_access' => $this->configuration['check_access'] ? $this->t('yes') : $this->t('no')]);
    $summary = [
      '#markup' => '(' . $markup . ')',
    ];

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'check_access' => FALSE,
      'view_name' => NULL,
      'view_display' => NULL,
      'view_arg' => NULL,
      'display_tab' => NULL,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $view_options = Views::getViewsAsOptions(TRUE, 'enabled', NULL, FALSE, TRUE);
    $user_input = $form_state->getUserInput();
    $data = $user_input['data'] ?? [];
    $default_view_name = $data['view_name'] ?? $this->configuration['view_name'];
    $form['view_name'] = [
      '#type' => 'select',
      '#title' => $this->t('view name'),
      '#options' => $view_options,
      '#default_value' => $default_view_name,
      // '#field_suffix' => '',
      // Drupal\blocktabs\Plugin\Tab\ViewsTab.
      '#ajax' => [
        'callback' => [$this, 'updateDisplay'],
        'wrapper' => 'edit-view-display-wrapper',
        'event' => 'change',
      ],
      '#required' => TRUE,
    ];

    $display_options = [];
    if ($default_view_name) {
      $view = Views::getView($default_view_name);
      foreach ($view->storage->get('display') as $name => $display) {
        $display_options[$name] = $display['display_title'] . ' (' . $display['id'] . ')';
      }
    }

    $form['view_display'] = [
      '#type' => 'select',
      '#title' => $this->t('Display'),
      '#default_value' => $this->configuration['view_display'],
      '#prefix' => '<div id="edit-view-display-wrapper">',
      '#suffix' => '</div>',
      '#options' => $display_options,
      '#validated' => TRUE,
      '#required' => TRUE,
    ];

    $form['view_arg'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Argument'),
      '#default_value' => $this->configuration['view_arg'],
	  '#description' => $this->t('Separate rrgument values with a "/".'),
      // '#field_suffix' => '',
    ];

    $form['check_access'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Check Access'),
      '#default_value' => $this->configuration['check_access'],
      '#description' => $this->t('Check access restriction to the View.'),
      '#required' => FALSE,
    ];

    $form['display_tab'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display tab even if the view has no result'),
      '#default_value' => $this->configuration['display_tab'] ?? 0,
    ];

    return $form;
  }

  /**
   * Update display option.
   */
  public function updateDisplay(array &$form, FormStateInterface $form_state) {
    return $form['data']['view_display'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['view_name'] = $form_state->getValue('view_name');
    $this->configuration['view_display'] = $form_state->getValue('view_display');
    $this->configuration['view_arg'] = $form_state->getValue('view_arg');
    $this->configuration['display_tab'] = $form_state->getValue('display_tab');
    $this->configuration['check_access'] = $form_state->getValue('check_access');
  }

  /**
   * {@inheritdoc}
   */
  public function getContent() {
    $tab_content = '';
    $view_name = $this->configuration['view_name'];
    $view_display = $this->configuration['view_display'];
    $view_args = !empty($this->configuration['view_arg']) ? explode("/", $this->configuration['view_arg']) : [];

    $view = Views::getView($view_name);
    if (!$view || (!empty($this->configuration['check_access']) && !$view->access($view_display))) {
      return $tab_content;
    }
    $view->setDisplay($view_display);
    //if (!empty($this->configuration['view_arg'])) {
    $view->setArguments($view_args);
    //}
    $view->execute();
    $count = count($view->result);
    // $tab_view = $view->render();
    if (!empty($this->configuration['display_tab']) || ($count > 0)) {
      // $view_arg = !empty($this->configuration['view_arg']) ?
      // $this->configuration['view_arg'] : NULL;
      $tab_content = [
        '#type' => 'view',
        '#name' => $view_name,
        '#display_id' => $view_display,
        '#arguments' => $view_args,
      ];
      //$tab_content = views_embed_view($view_name, $view_display, $view_arg);
	  //$tab_content = $view->render();
    }
    else {
      $tab_content = NULL;
    }
    return $tab_content;
  }

}
