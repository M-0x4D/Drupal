<?php

namespace Drupal\event_management\Plugin\Block;

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
class LatestEventsBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $events = $this->getLatestEvents();
    return [
      '#theme' => 'latest_events',
      '#events' => $events,
    ];
  }


  /**
   * Fetches the latest 5 created events.
   *
   * @return array
   *   An array of event objects.
   */
  protected function getLatestEvents()
  {
    $database = Database::getConnection();
    $query = $database->select('events', 'e')
      ->fields('e')
      ->orderBy('created', 'DESC')
      ->range(0, 5)
      ->execute();
    return $query->fetchAll();
  }

}
