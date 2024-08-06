<?php

namespace Drupal\event_management\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Database\Database;

/**
 * Controller for displaying event details.
 */
class EventDetailController extends ControllerBase {

  protected $database;

  public function __construct()
  {
    $this->database = \Drupal::database();
  }

  /**
   * Displays event details.
   *
   * @param int $event_id
   *   The ID of the event.
   *
   * @return array
   *   Render array.
   */
  public function view($event_id) {
    // Fetch event details from the database.
    $database = $this->database;
    $query = $database->select('events', 'e')
      ->fields('e')
      ->condition('id', $event_id)
      ->execute();
    $event = $query->fetchAssoc();

    if (!$event) {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }

    // Return the render array.
    return [
      '#theme' => 'event_detail',
      '#event' => $event,
    ];
  }
}
