<?php

namespace Drupal\event_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\event_management\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Drupal\file\Entity\File;

/**
 * Class EventForm.
 */
class EventForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'event_form';
  }

  protected function getCategories()
  {
    $database = \Drupal::database();
    $query = $database->select('categories', 'c')
      ->fields('c', ['id', 'name'])
      ->execute();
    $categories = $query->fetchAllKeyed();

    return $categories;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Event $event = NULL)
  {
    $categories = $this->getCategories();
    $default_option = ['' => $this->t('Select a category')];
    $default_value = $event ? $event->getCategoryId() : '';


    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $event ? $event->id() : '',
    ];

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
      '#default_value' => $event ? $event->getTitle() : '',
    ];

    $form['image'] = [
      '#type' => 'file',
      '#title' => $this->t('Event Image'),
      '#upload_location' => 'public://event_images/',
      '#required' => FALSE,
      '#default_value' => $event ? [$event->getImage()] : NULL,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#required' => TRUE,
      '#default_value' => $event ? $event->getDescription() : '',
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

    $form['category_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#options' => $default_option + $categories,
      '#required' => TRUE,
      '#default_value' => $default_value,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];


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
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    $event = Event::load($form_state->getValue('id')) ?: Event::create();

    if (!empty($values['image'])) {
      $image = $values['image'];
      $file = File::load($image[0]);
      $file->setPermanent();
      $file->save();
      $event->set('image', $file->id());
    }

    $title = $form_state->getValue('title');
    $description = $form_state->getValue('description');
    $start_date = $form_state->getValue('start_date');
    $end_date = $form_state->getValue('end_date');
    $category_id = $form_state->getValue('category_id');
    $event->setTitle($title);
    $event->setCategoryId($category_id);
    $event->setDescription($description);
    $event->setStartDate($start_date);
    $event->setEndDate($end_date);
    $event->save();

    \Drupal::messenger()->addMessage($this->t('Event saved successfully.'));
    $form_state->setRedirect('event_management.list');
  }
}
