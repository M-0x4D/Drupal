<?php

namespace Drupal\event_management\Controller;

use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Drupal\event_management\Entity\Event;

/**
 * Returns responses for Example Module routes.
 */
class EventManagementController extends ControllerBase
{

  protected $database;

  public function __construct()
  {
    $this->database = \Drupal::database();
  }
//
//  function dbConnection()
//  {
//    return \Drupal::database();
//  }


  public function listEvents() {
    try {

      $database = $this->database;
      $query = $database->select('events', 'e')
        ->fields('e')
        ->execute();
      $events = $query->fetchAllAssoc('id');

      return [
        '#theme' => 'event_listing',
        '#events' => $events,
      ];
    }catch (\Throwable $th){
      \Drupal::logger('event_management')->notice('error : @error', ['@error' => $th->getMessage()]);
      dd($th->getMessage());
    }
  }


  /**
   * Display a list of events.
   *
   * @return array
   */
  public function list()
  {

    $database = $this->database;
      $query = $database->select('events', 'e')
        ->fields('e')
        ->execute();
      $events = $query->fetchAllAssoc('id');
//    $events = Event::loadMultiple();

    $header = [
      'id' => $this->t('ID'),
      'title' => $this->t('Title'),
      'description' => $this->t('Description'),
//      'start_date' => $this->t('Start Date'),
//      'end_date' => $this->t('End Date'),
      'category_id' => $this->t('Category ID'),
    ];

    $rows = [];
    foreach ($events as $event) {
      $rows[] = [
        'id' => $event->id,
        'title' => $event->title,
        'description' => $event->description,
//        'start_date' => $event->start_date->value,
//        'end_date' => $event->end_date->value,
        'category_id' => $event->category_id,
      ];
    }


    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  }
}
