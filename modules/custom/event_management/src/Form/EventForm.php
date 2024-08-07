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

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Event $event = NULL)
  {
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
      '#type' => 'managed_file',
      '#title' => $this->t('Image'),
      '#upload_location' => 'public://events/',
      '#required' => false,
      '#upload_validators' => [
//        'file_validate_extensions' => ['png jpg jpeg'],
      ],
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#required' => TRUE,
      '#default_value' => $event ? $event->getDescription() : '',
    ];

//    $form['start_date'] = [
//      '#type' => 'datetime',
//      '#title' => $this->t('Start Date'),
//      '#required' => TRUE,
//      '#default_value' => $event ? $event->getStartDate() : '',
//    ];
//
//    $form['end_date'] = [
//      '#type' => 'datetime',
//      '#title' => $this->t('End Date'),
//      '#required' => TRUE,
//      '#default_value' => $event ? $event->getEndDate() : '',
//    ];

    $form['category_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Category ID'),
      '#required' => TRUE,
      '#default_value' => $event ? $event->getCategoryId() : '',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    // If editing an existing event, load it, otherwise create a new one.
    $event = Event::load($form_state->getValue('id')) ?: Event::create();

    $title = $form_state->getValue('title');
    $description = $form_state->getValue('description');
    $start_date = $form_state->getValue('start_date');
    $end_date = $form_state->getValue('end_date');
    $category_id = $form_state->getValue('category_id');
    $file_ids = $form_state->getValue('image');

    // Validate and process the file.
    if (!empty($file_ids) && is_array($file_ids)) {
      $file_id = reset($file_ids); // Get the first file ID.

      $file = File::load($file_id);

      if ($file) {
        // Ensure the file is marked as permanent.
        $file->setPermanent();
        $file->save();

        // Get the file URI.
        // Get the file URI and store it.
        $file_uri = $file->getFileUri();
        $file_path = \Drupal::service('file_system')->realpath($file_uri);
      } else {
        $this->messenger()->addError($this->t('Failed to load the file.'));
        return;
      }
    }

    $event->setTitle($title);
    $event->setImage($file_path);
    $event->setCategoryId($category_id);
    $event->setDescription($description);
    $event->save();

    \Drupal::messenger()->addMessage($this->t('Event saved successfully.'));
    $form_state->setRedirect('event_management.list');
  }
}
