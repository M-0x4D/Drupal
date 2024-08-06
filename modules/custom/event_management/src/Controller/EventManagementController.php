<?php

namespace Drupal\event_management\Controller;

use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;


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

  public function add(Request $request) {
    // Add logic to handle event creation.
    return [
      '#title' => 'tkjh'
    ];
  }

  public function edit(Node $event, Request $request) {
    // Add logic to handle event editing.
    dd($event);

    return [
    ];
  }

  public function delete(Node $event, Request $request) {
    // Add logic to handle event deletion.
  }

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

}
