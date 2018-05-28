<?php

/**
 * Contains \Drupal\field_collection\FieldCollectionMigrationTranslationTransform.
 */

namespace Drupal\field_collection;

/**
 * Transforms item_id for multilingual migrations.
 */
class FieldCollectionMigrationTranslationTransform {

  public function setTranslationItemID ($tnid, $delta) {
    return $tnid . "0" . $delta;
  }

}