<?php

namespace Drupal\event_management\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines the Event entity.
 *
 * @ContentEntityType(
 *   id = "event",
 *   label = @Translation("Event"),
 *   base_table = "events",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *   },
 * )
 */
class Event extends ContentEntityBase implements EntityInterface {

  /**
   * The description of the event.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRequired(TRUE);

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Description'))
      ->setRequired(TRUE);

    $fields['image'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Image'))
      ->setRequired(FALSE)
      ->setSetting('target_type', 'file')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'file_default',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'file_generic',
        'weight' => 0,
        'settings' => [
          'file_extensions' => 'png jpg jpeg',
          'file_directory' => 'event_images',
          'max_filesize' => '5 MB',
          'description_field' => FALSE,
        ],
      ]);

    $fields['start_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Start Date'))
      ->setRequired(TRUE);

    $fields['end_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('End Date'))
      ->setRequired(TRUE);

    $fields['category_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Category'))
      ->setRequired(TRUE);

    return $fields;
  }

  /**
   * Get the title.
   *
   * @return string
   *   The title of the event.
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * Set the title.
   *
   * @param string $title
   *   The title of the event.
   */
  public function setTitle($title) {
    $this->set('title', $title);
  }





  public function getImage() {
    return $this->get('image')->entity;
  }

  public function setImage($image) {
    $this->set('image', $image);
  }




  /**
   * Get the description.
   *
   * @return string
   *   The description of the event.
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * Set the description.
   *
   * @param string $description
   *   The description of the event.
   */
  public function setDescription($description) {
    $this->set('description', $description);
  }

  /**
   * Get the start date.
   *
   * @return string
   *   The start date of the event.
   */
  public function getStartDate() {
    return $this->get('start_date')->value;
  }

  /**
   * Set the start date.
   *
   * @param string $start_date
   *   The start date of the event.
   */
  public function setStartDate($start_date) {
    $this->set('start_date', $start_date);
  }

  /**
   * Get the end date.
   *
   * @return string
   *   The end date of the event.
   */
  public function getEndDate() {
    return $this->get('end_date')->value;
  }

  /**
   * Set the end date.
   *
   * @param string $end_date
   *   The end date of the event.
   */
  public function setEndDate($end_date) {
    $this->set('end_date', $end_date);
  }

  /**
   * Get the category ID.
   *
   * @return int
   *   The category ID of the event.
   */
  public function getCategoryId() {
    return $this->get('category_id')->value;
  }

  /**
   * Set the category ID.
   *
   * @param int $category_id
   *   The category ID of the event.
   */
  public function setCategoryId($category_id) {
    $this->set('category_id', $category_id);
  }
}
