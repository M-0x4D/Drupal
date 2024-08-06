<?php

namespace Drupal\event_management\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Latest Events' Block.
 *
 * @Block(
 *   id = "latest_events_block",
 *   admin_label = @Translation("Latest Events Block"),
 *   category = @Translation("Custom")
 * )
 */
class LatestEventsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Query the latest 5 published events.
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'event')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->range(0, 5);

    $nids = $query->execute();
    $nodes = Node::loadMultiple($nids);

    return [
      '#theme' => 'latest_events_block',
      '#events' => $nodes,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
