<?php

namespace Drupal\event_management\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\event_management\Entity\Event;

/**
 * Controller for deleting an event.
 */
class EventDeleteController extends ControllerBase {

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
