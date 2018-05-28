<?php

namespace Drupal\field_collection\Plugin\migrate\source\d7;

use Drupal\migrate\Row;
use Drupal\node\Plugin\migrate\source\d7\Node;

/**
 * Drupal 7 node source from database with modifications for field collections.
 *
 * @MigrateSource(
 *   id = "d7_node_fci",
 *   source_provider = "node"
 * )
 */
class NodeWithFieldCollection extends Node {

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    if (!parent::prepareRow($row)) {
      return FALSE;
    }

    $this->handleFieldCollectionItems($row);

    return TRUE;
  }

  protected function handleFieldCollectionItems(Row $row) {
    if ($field_collection_fields = $this->configuration['field_collection_fields']) {
      foreach ($field_collection_fields as $field_collection_name) {
        if ($field_collection_item = $row->getSourceProperty($field_collection_name)) {
          $tnid = $row->getSourceProperty('tnid');
          foreach ($field_collection_item as $delta => $values) {
            $field_collection_item[$delta]['value'] = \Drupal::service('field_collection.migration_translation_transform')->setTranslationItemID($tnid, $delta);
          }
          $row->setSourceProperty($field_collection_name, $field_collection_item);
        }
      }
    }
  }
}
