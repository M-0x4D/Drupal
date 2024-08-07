<?php

namespace Drupal\event_management\EventSubscriber;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConfigChangeSubscriber implements EventSubscriberInterface
{

  protected $database;
  protected $currentUser;

  public function __construct(Connection $database, AccountProxyInterface $currentUser)
  {
    $this->database = $database;
    $this->currentUser = $currentUser;
  }

  public static function getSubscribedEvents()
  {
    $events[ConfigEvents::SAVE][] = ['onConfigSave'];
    return $events;
  }

  public function onConfigSave(ConfigCrudEvent $event)
  {
    $config = $event->getConfig();
    $config_name = $config->getName();
    $config_data = $config->getRawData();

// Only log changes for the specific config you are interested in.
    if ($config_name == 'event_management.settings') {
      $this->database->insert('event_management_config_log')
        ->fields([
          'uid' => $this->currentUser->id(),
          'timestamp' => \Drupal::time()->getRequestTime(),
          'config_name' => $config_name,
          'config_data' => json_encode($config_data),
        ])
        ->execute();
    }
  }
}
