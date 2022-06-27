<?php

namespace Drupal\contact_tools\Twig\Extension;

use Drupal\contact_tools\Service\ContactTools;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Custom twig function for contact tools.
 */
class Extensions extends AbstractExtension {

  /**
   * Contact tools service.
   *
   * @var \Drupal\contact_tools\Service\ContactTools
   */
  protected ContactTools $contactTools;

  /**
   * Constructs a new Extensions object.
   *
   * @param \Drupal\contact_tools\Service\ContactTools $contact_tools
   *   The contact tools service.
   */
  public function __construct(ContactTools $contact_tools) {
    $this->contactTools = $contact_tools;
  }

  /**
   * Returns the name of the extension.
   *
   * @return string
   *   Extension name.
   */
  public function getName(): string {
    return 'contact_tools';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions(): array {
    $functions = [];

    $functions[] = new TwigFunction('contact_form', [$this, 'contactForm']);
    $functions[] = new TwigFunction('contact_form_ajax', [
      $this,
      'contactFormAjax',
    ]);
    $functions[] = new TwigFunction('contact_modal', [$this, 'contactModal']);
    $functions[] = new TwigFunction('contact_modal_ajax', [
      $this,
      'contactModalAjax',
    ]);

    return $functions;
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactFormAjax(string $contact_form_id = 'default_form', array $form_state_additions = []): array {
    return $this->contactTools->getFormAjax($contact_form_id, $form_state_additions);
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactForm(string $contact_form_id = 'default_form', array $form_state_additions = []): array {
    return $this->contactTools->getForm($contact_form_id, $form_state_additions);
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactModal(string $link_title, string $contact_form, array $link_options = [] /*string $key = 'default'*/): array {
    return $this->contactTools->createModalLink($link_title, $contact_form, $link_options, /*$key*/);
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactModalAjax($link_title, $contact_form, $link_options = [] /*$key = 'default-ajax'*/) {
    return $this->contactTools->createModalLinkAjax($link_title, $contact_form, $link_options, /*$key*/);
  }

}
