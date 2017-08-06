<?php

namespace Drupal\contact_tools;

/**
 * Custom twig function for contact tools.
 */
class TwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'contact_tools';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('contact_form_ajax', [$this, 'contactFormAjax']),
      new \Twig_SimpleFunction('contact_modal', [$this, 'contactModal']),
      new \Twig_SimpleFunction('contact_modal_ajax', [$this, 'contactModalAjax']),
    ];
  }

  /**
   * Return form render array with AJAX support.
   *
   * @param string $contact_form_id
   *
   * @return array
   */
  public function contactFormAjax($contact_form_id = 'default_form') {
    $contect_tools = \Drupal::service('contact_tools');
    return $contect_tools->getFormAjax($contact_form_id);
  }

  /**
   * Return form render array with AJAX support.
   *
   * @param       $link_title
   * @param       $contact_form
   * @param array $url_options
   * @param array $link_options
   *
   * @return array
   * @internal param string $contact_form_id
   *
   */
  public function contactModal($link_title, $contact_form, $url_options = [], $link_options = []) {
    $contect_tools = \Drupal::service('contact_tools');
    return $contect_tools->createModalLink($link_title, $contact_form, $url_options, $link_options);
  }

  /**
   * Return form render array with AJAX support.
   *
   * @param       $link_title
   * @param       $contact_form
   * @param array $url_options
   * @param array $link_options
   *
   * @return array
   * @internal param string $contact_form_id
   *
   */
  public function contactModalAjax($link_title, $contact_form, $url_options = [], $link_options = []) {
    $contect_tools = \Drupal::service('contact_tools');
    return $contect_tools->createModalLinkAjax($link_title, $contact_form, $url_options, $link_options);
  }

}
