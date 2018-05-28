<?php

namespace Drupal\features\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\features\FeaturesAssignerInterface;

/**
 * Class ConfigSaveSubscriber.
 */
class ConfigSaveSubscriber implements EventSubscriberInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The features assigner.
   *
   * @var \Drupal\features\FeaturesAssignerInterface
   */
  protected $assigner;

  /**
   * Constructs a new ConfigSaveSubscriber object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *    The entity type manager.
   * @param \Drupal\features\FeaturesAssignerInterface $assigner
   *   The configuration assignment methods manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FeaturesAssignerInterface $assigner) {
    $this->entityTypeManager = $entity_type_manager;
    $this->assigner = $assigner;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[ConfigEvents::SAVE] = ['onConfigSave'];

    return $events;
  }

  /**
   * Sets a features bundle as current when it is saved.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   The Event to process.
   */
  public function onConfigSave(ConfigCrudEvent $event) {
    $config = $event->getConfig();
    if (strpos($config->getName(), 'features.bundle.') === 0) {
      /** @var \Drupal\features\Entity\FeaturesBundle $bundle */
      $bundle = $this->entityTypeManager->getStorage('features_bundle')->load($config->get('machine_name'));
      $this->assigner->setBundle($bundle, TRUE);
    }
  }

}
