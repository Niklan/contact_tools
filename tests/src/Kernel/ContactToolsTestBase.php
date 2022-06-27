<?php

declare(strict_types=1);

namespace Drupal\Tests\contact_tools\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Provides a base class for Kernel tests.
 */
abstract class ContactToolsTestBase extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'contact_tools',
    'contact',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

}
