<?php

declare(strict_types=1);

namespace Drupal\Tests\contact_tools\Kernel\Plugin\Filter;

use Drupal\Core\Language\LanguageInterface;
use Drupal\filter\FilterPluginManager;
use Drupal\Tests\contact_tools\Kernel\ContactToolsTestBase;

/**
 * Provides test for modal link filter.
 *
 * @coversDefaultClass \Drupal\contact_tools\Plugin\Filter\ModalLinkFilter
 * @group contact_tools
 */
final class ModalLinkFilterTest extends ContactToolsTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['filter'];

  /**
   * The filter plugin manager.
   *
   * @var \Drupal\filter\FilterPluginManager|null
   */
  protected ?FilterPluginManager $filterPluginManager;

  /**
   * Tests that filter works as expected.
   */
  public function testFilter(): void {
    $definitions = $this->filterPluginManager->getDefinitions();
    self::assertArrayHasKey('contact_tools_modal_link', $definitions);

    $html = '<a href="/contact-tools/test">Test link</a>';
    /** @var \Drupal\contact_tools\Plugin\Filter\ModalLinkFilter $plugin */
    $plugin = $this->filterPluginManager
      ->createInstance('contact_tools_modal_link');

    $result = $plugin->process($html, LanguageInterface::LANGCODE_NOT_SPECIFIED);

    $attachments = [
      'library' => [
        'core/drupal.dialog.ajax',
      ],
    ];
    self::assertEquals($attachments, $result->getAttachments());

    $expected_html = <<<'HTML'
    <a href="/contact-tools/test" class=" use-ajax" data-dialog-type="modal" data-dialog-options='{"width":"auto","dialogClass":"contact-tools-modal"}'>Test link</a>
    HTML;
    self::assertEquals($expected_html, $result->getProcessedText());
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->filterPluginManager = $this->container->get('plugin.manager.filter');
  }

}
