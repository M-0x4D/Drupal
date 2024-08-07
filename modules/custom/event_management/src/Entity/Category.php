<?php

namespace Drupal\event_management\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Category entity.
 *
 * @ContentEntityType(
 *   id = "category",
 *   label = @Translation("Category"),
 *   base_table = "categories",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 * )
 */
class Category extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setRequired(TRUE)
      ->setReadOnly(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 255,
      ]);

    return $fields;
  }
}
