<?php

namespace Drupal\event_management\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\event_management\Entity\Event;

/**
 * Class EventDeleteForm.
 */
class EventDeleteForm extends ConfirmFormBase {

  /**
   * The ID of the event to delete.
   *
   * @var int
   */
  protected $id;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the event?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.event.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $event = Event::load($this->id);
    dd($event);
    $event->delete();

    \Drupal::messenger()->addMessage($this->t('Event deleted successfully.'));
    $form_state->setRedirect('<front>');
  }
}
