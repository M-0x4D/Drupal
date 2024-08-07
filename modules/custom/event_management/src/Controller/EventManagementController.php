<?php

namespace Drupal\event_management\Controller;

use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Drupal\event_management\Entity\Event;
use Drupal\Core\Link;
use Drupal\Core\Url;

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


  public function listEvents()
  {
    try {
      // Fetch configuration settings
      $config = $this->config('event_management.settings');
      $events_per_page = $config->get('events_per_page');
      $show_past_events = $config->get('show_past_events');

      $database = $this->database;
      $query = $database->select('events', 'e')
        ->fields('e');

      // Condition to show or hide past events
      if (!$show_past_events) {
        $query->condition('start_date', date('Y-m-d H:i:s'), '>=');
      }

      // Count total events for pagination
      $count_query = clone $query;
      $total_events = $count_query->countQuery()->execute()->fetchField();

      // Pager
      $pager = \Drupal::service('pager.manager')->createPager($total_events, $events_per_page);
      $current_page = $pager->getCurrentPage();
      $offset = $current_page * $events_per_page;

      // Set the range for pagination
      $query->range($offset, $events_per_page);

      $result = $query->execute();
      $events = $result->fetchAllAssoc('id');

      return [
        '#theme' => 'event_listing',
        '#events' => $events,
        '#pager' => [
          '#type' => 'pager',
        ],
      ];

    } catch (\Throwable $th) {
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
      ['data' => t('ID'), 'field' => 'id'],
      ['data' => t('Title'), 'field' => 'title'],
      ['data' => t('Description'), 'field' => 'description'],
      ['data' => t('Category ID'), 'field' => 'category_id'],
      ['data' => t('Actions')],
    ];

    $rows = [];
    foreach ($events as $event) {

      // Create the edit link.
      $edit_url = Url::fromRoute('event_management.edit', ['event' => (int)$event->id]);
      $edit_link = Link::fromTextAndUrl(t('Edit'), $edit_url)->toString();

      // Create the delete link.
      $delete_url = Url::fromRoute('event_management.delete', ['event' => (int)$event->id]);
      $delete_link = Link::fromTextAndUrl(t('Delete'), $delete_url)->toString();

      $rows[] = [
        'id' => $event->id,
        'title' => $event->title,
        'description' => $event->description,
//        'start_date' => $event->start_date->value,
//        'end_date' => $event->end_date->value,
        'category_id' => $event->category_id,
        'actions' => [
          'data' => [
            '#markup' => $edit_link . ' | ' . $delete_link,
          ],
        ]
      ];
    }


    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  }
}
