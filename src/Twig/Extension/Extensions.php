<?php

namespace Drupal\contact_tools\Twig\Extension;

/**
 * Custom twig function for contact tools.
 */
class Extensions extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    $functions = [];

    $functions[] = new \Twig_SimpleFunction('contact_form', [$this, 'contactForm']);
    $functions[] = new \Twig_SimpleFunction('contact_form_ajax', [$this, 'contactFormAjax']);
    $functions[] = new \Twig_SimpleFunction('contact_modal', [$this, 'contactModal']);
    $functions[] = new \Twig_SimpleFunction('contact_modal_ajax', [$this, 'contactModalAjax']);

    return $functions;
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactFormAjax($contact_form_id = 'default_form') {
    $contact_tools = \Drupal::service('contact_tools');
    return $contact_tools->getFormAjax($contact_form_id);
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactForm($contact_form_id = 'default_form') {
    $contact_tools = \Drupal::service('contact_tools');
    return $contact_tools->getForm($contact_form_id);
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactModal($link_title, $contact_form, $link_options = [], $key = 'default') {
    $contact_tools = \Drupal::service('contact_tools');
    return $contact_tools->createModalLink($link_title, $contact_form, $link_options, $key);
  }

  /**
   * Return form render array with AJAX support.
   */
  public function contactModalAjax($link_title, $contact_form, $link_options = [], $key = 'default-ajax') {
    $contact_tools = \Drupal::service('contact_tools');
    return $contact_tools->createModalLinkAjax($link_title, $contact_form, $link_options, $key);
  }

}
