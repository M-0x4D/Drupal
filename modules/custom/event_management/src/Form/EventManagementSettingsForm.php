<?php

namespace Drupal\event_management\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class EventManagementSettingsForm extends ConfigFormBase
{

  protected function getEditableConfigNames()
  {
    return ['event_management.settings'];
  }

  public function getFormId()
  {
    return 'event_management_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('event_management.settings');

    $form['show_past_events'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Past Events'),
      '#default_value' => $config->get('show_past_events'),
    ];

    $form['events_per_page'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of events per page'),
      '#default_value' => $config->get('events_per_page'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    try {

      //log into logs table when this form submitted


      $this->config('event_management.settings')
        ->set('show_past_events', $form_state->getValue('show_past_events'))
        ->set('events_per_page', $form_state->getValue('events_per_page'))
        ->save();

      parent::submitForm($form, $form_state);
    } catch (\Throwable $th) {
      dd($th->getMessage());
    }

  }

}
