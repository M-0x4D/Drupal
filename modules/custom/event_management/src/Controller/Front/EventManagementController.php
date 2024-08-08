<?php

namespace Drupal\event_management\Controller\Front;

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
      if (!!$show_past_events) {
        $query->condition('start_date', date('Y-m-d H:i:s'), '>=');
      }

      // Count total events for pagination
      $count_query = $database->select('events', 'e')
        ->countQuery();
      $total_events = $count_query->execute()->fetchField();


      if (!!$events_per_page) {
        // Pager
        $pager = \Drupal::service('pager.manager')->createPager($total_events, $events_per_page);

        $current_page = $pager->getCurrentPage();
        $offset = $current_page * $events_per_page;
        $query->range($offset, $events_per_page);

      }


      // Set the range for pagination

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


}
