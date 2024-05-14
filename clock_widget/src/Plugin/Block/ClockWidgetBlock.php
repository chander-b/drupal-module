<?php

namespace Drupal\clock_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Clock Widget' block.
 *
 * @Block(
 *   id = "clock_widget",
 *   admin_label = @Translation("Clock Widget"),
 *   category = @Translation("Custom"),
 * )
 */
class ClockWidgetBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $timezone = $this->configuration['timezone'];

    return [
      '#theme' => 'clock_widget_template',
      '#timezone' => $timezone,
      '#attached' => [
        'library' => [
          'clock_widget/clock-widget',
        ],
        'drupalSettings' => [
          'timezone' => $timezone,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Select time zone'),
      '#default_value' => $config['timezone'] ?? '',
      '#options' => [
        'est' => $this->t('Eastern Standard Time (EST)'),
        'utc' => $this->t('Coordinated Universal Time (UTC)'),
      ],
      '#required' => TRUE,
      '#attributes' => ['class' => ['timezone']],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['timezone'] = $form_state->getValue('timezone');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), [$this->configuration['timezone']]);
  }

}
