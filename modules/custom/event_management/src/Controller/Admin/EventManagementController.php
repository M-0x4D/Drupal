<?php

namespace Drupal\event_management\Controller\Admin;

use Drupal\Core\Entity\EntityStorageException;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

  /**
   * Display a list of events.
   *
   * @return array
   */
  public function list()
  {
    // Define the URL for the "Create New Event" button
    $create_event_url = Url::fromRoute('event_management.add'); // Make sure this route matches your defined route for adding events
    $create_event_link = Link::fromTextAndUrl(t('Create New Event'), $create_event_url)->toString();

    // Create the button
    $create_event_button = [
      '#type' => 'markup',
      '#markup' => '<div class="create-event-button">' . $create_event_link . '</div>',
    ];
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
      ['data' => t('Start Date'), 'field' => 'start_date'],
      ['data' => t('End Date'), 'field' => 'end_date'],
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
        'category_id' => $event->category_id,
        'start_date' => $event->start_date,
        'end_date' => $event->end_date,
        'actions' => [
          'data' => [
            '#markup' => $edit_link . ' | ' . $delete_link,
          ],
        ]
      ];
    }

    // Build the render array
    $build = [
      'create_event_button' => $create_event_button,
      'events_table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
      ],
    ];

    return $build;
  }


  /**
   * Deletes the event and redirects.
   */
  public function delete(Request $request, $event) {
    $entity = Event::load($event);

    if ($entity) {
      try {
        $entity->delete();
        \Drupal::messenger()->addMessage($this->t('Event %title has been deleted.', ['%title' => $entity->get('title')->value]));
      }
      catch (EntityStorageException $e) {
        \Drupal::messenger()->addError($this->t('Unable to delete event %title.', ['%title' => $entity->get('title')->value]));
      }
    }
    else {
      \Drupal::messenger()->addError($this->t('Event not found.'));
    }

    return new RedirectResponse('/admin/events');
  }
}
