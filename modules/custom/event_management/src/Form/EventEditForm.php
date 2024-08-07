<?php

namespace Drupal\event_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\event_management\Entity\Event;

/**
 * Form for editing an event.
 */
class EventEditForm extends FormBase
{


  public function validateForm(array &$form, FormStateInterface $form_state)
  {
//    parent::validateForm($form, $form_state); // TODO: Change the autogenerated stub
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'event_edit_form';
  }

  /**
   * Builds the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
  {
    $event = Event::load((int)$id);


    // If the event is not loaded, return an error message.
    if (!$event) {
      \Drupal::messenger()->addError($this->t('Event not found.'));
      return [];
    }
    // Create the form elements.
    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $event ? (int) $event->id() : '',
    ];

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $event ? $event->get('title')->value : '',
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $event ? $event->get('description')->value : '',
    ];

    $form['category_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Category ID'),
      '#default_value' => $event ? $event->get('category_id')->value : '',
    ];

    // Add other fields as needed.

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
      ],
    ];

    return $form;
  }

  /**
   * Handles form submission.
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $event_id = $form_state->getValue('id');
    $event = Event::load($event_id);

    if ($event) {
      $event->set('title', $form_state->getValue('title'));
      $event->set('description', $form_state->getValue('description'));
      $event->set('category_id', $form_state->getValue('category_id'));

      // Save the entity.
      $event->save();
      \Drupal::messenger()->addMessage($this->t('Event updated successfully.'));
    } else {
      \Drupal::messenger()->addError($this->t('Event not found.'));
    }
  }
}