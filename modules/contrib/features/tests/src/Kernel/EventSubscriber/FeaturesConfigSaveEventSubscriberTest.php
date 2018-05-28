<?php

namespace Drupal\Tests\features\Kernel\EventSubscriber;

use Drupal\features\Entity\FeaturesBundle;
use Drupal\KernelTests\KernelTestBase;

/**
 * @coversDefaultClass \Drupal\features\EventSubscriber\ConfigSaveSubscriber
 * @group features
 */
class FeaturesConfigSaveEventSubscriberTest extends KernelTestBase {

  /**
   * The features assigner.
   *
   * @var \Drupal\features\FeaturesAssignerInterface
   */
  protected $assigner;

  /**
   * {@inheritdoc}
   */
  public static $modules  = ['features'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig('features');
    $this->entityTypeManager = $this->container->get('features_assigner');
    $this->assigner = $this->container->get('features_assigner');
  }

  public function testConfigSaveEventSubscriber() {
    $bundle = FeaturesBundle::create([
      'machine_name' => 'test',
      'name' => 'Test',
    ]);
    $bundle->save();

    // Get the current bundle.
    /** @var \Drupal\features\Entity\FeaturesBundleInterface $bundle */
    $bundle = $this->assigner->getBundle();
    $this->assertEquals('test', $bundle->getMachineName());
  }

}
