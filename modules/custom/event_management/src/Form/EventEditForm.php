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

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'event_edit_form';
  }

  protected function getCategories() {
    $database = \Drupal::database();
    $query = $database->select('categories', 'c')
      ->fields('c', ['id', 'name'])
      ->execute();
    $categories = $query->fetchAllKeyed();

    return $categories;
  }

  /**
   * Builds the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state, Event $event = NULL)
  {
    $categories = $this->getCategories();

    // Add a default option.
    $default_option = ['' => $this->t('Select a category')];

    // Determine the default value for the category_id field.


    if ($event) {
      // Load the event entity if not directly passed.
      $event = $event->isNew() ? NULL : $event;
    } else {
      $event_id = \Drupal::routeMatch()->getParameter('event');
      $event = Event::load($event_id);
    }

    // If the event is not loaded, return an error message.
    if (!$event) {
      \Drupal::messenger()->addError($this->t('Event not found.'));
      return [];
    }

    $default_value = $event ?(int) $event->get('category_id') : '';
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
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#options' => $default_option + $categories,
      '#required' => TRUE,
      '#default_value' => $default_value,
    ];



    $form['start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Start Date'),
      '#required' => TRUE,
      '#default_value' => $event ? $event->getStartDate() : '',
    ];

    $form['end_date'] = [
      '#type' => 'date',
      '#title' => $this->t('End Date'),
      '#required' => TRUE,
      '#default_value' => $event ? $event->getEndDate() : '',
    ];

    // Add other fields as needed.

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
      ],
    ];

    // Add attributes to disable the default option if no category is selected.
    if ($default_value === '') {
      $form['category_id']['#options']['']['disabled'] = 'disabled';
    }

    return $form;
  }




  public function validateForm(array &$form, FormStateInterface $form_state) {
    $start_date = $form_state->getValue('start_date');
    $end_date = $form_state->getValue('end_date');

    if ($start_date && $end_date) {
      $start_date_timestamp = strtotime($start_date);
      $end_date_timestamp = strtotime($end_date);

      if ($end_date_timestamp <= $start_date_timestamp) {
        $form_state->setErrorByName('end_date', $this->t('End date must be later than start date.'));
      }
    }
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
      $event->set('start_date', $form_state->getValue('start_date'));
      $event->set('end_date', $form_state->getValue('end_date'));
      // Save the entity.
      $event->save();
      \Drupal::messenger()->addMessage($this->t('Event updated successfully.'));
    } else {
      \Drupal::messenger()->addError($this->t('Event not found.'));
    }
  }
}
