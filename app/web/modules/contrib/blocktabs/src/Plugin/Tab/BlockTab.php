<?php

namespace Drupal\blocktabs\Plugin\Tab;

use Drupal\Core\Block\BlockManagerInterface;
use Psr\Log\LoggerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\blocktabs\ConfigurableTabBase;
use Drupal\blocktabs\BlocktabsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Block tab.
 *
 * @Tab(
 *   id = "block_tab",
 *   label = @Translation("block plugin tab"),
 *   description = @Translation("block plugin tab.")
 * )
 */
class BlockTab extends ConfigurableTabBase {

  /**
   * The block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected $blockManager;

  /**
   * The block plugin.
   *
   * @var \Drupal\Core\Block\BlockPluginInterface
   */
  protected $blockPlugin;

  /**
   * The current User.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('blocktabs'),
      $container->get('plugin.manager.block'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerInterface $logger, BlockManagerInterface $block_manager, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);

    $config = $this->configuration['config'];
    $this->blockManager = $block_manager;
    $this->blockPlugin = $this->blockManager->createInstance($this->configuration['block_id'], $config);
    $this->currentUser = $current_user;
  }

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
    $summary = [
      '#markup' => '(' . $this->t('Block plugin id:') . $this->configuration['block_id'] . ')',
    ];
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'block_id' => NULL,
      'config' => [],
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $definitions = $this->blockManager->getGroupedDefinitions();

    $options = [];
    foreach ($definitions as $group => $blocks) {
      $options[$group] = [];

      foreach ($blocks as $id => $block) {
        $options[$group][$id] = $block['admin_label'];
      }
    }

    $form['block_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Block id'),
      '#options' => $options,
      '#default_value' => $this->configuration['block_id'],
      '#required' => TRUE,
    ];

    if ($this->configuration['block_id']) {
      $form += $this->blockPlugin->blockForm([], $form_state);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['block_id'] = $form_state->getValue('block_id');

    $this->blockPlugin->blockSubmit($form, $form_state);
    $this->configuration['config'] = $this->blockPlugin->getConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function getContent() {
    $tab_content = [];
    if ($this->blockPlugin->access($this->currentUser)) {
      $tab_content = $this->blockPlugin->build();
    }

    return $tab_content;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return $this->blockPlugin->getCacheContexts();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return $this->blockPlugin->getCacheTags();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return $this->blockPlugin->getCacheMaxAge();
  }

}
