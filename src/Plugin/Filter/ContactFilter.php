<?php

/**
 * Contains Drupal\contact_tools\Plugin\Filter\ContactFilter
 */

namespace Drupal\contact_tools\Plugin\Filter;

use Drupal\Component\Serialization\Json;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;

/**
 * @Filter(
 *   id = "contact_tools_contact",
 *   title = @Translation("Contact Tools filter"),
 *   description = @Translation("Widget"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE
 * )
 */
class ContactFilter extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (preg_match_all("/\\[contact\\](.*?)\\[\\/contact\\]/s", $text, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $match) {
        $element = $match[0];
        $settings = Json::decode($match[1]);
        $contact_tools = \Drupal::service('contact_tools');
        $replace = '';

        switch ($settings['type']) {
          case 'modalLink':
            $url_options = !empty($settings['url_options']) ? $settings['url_options'] : [];
            $link_options = !empty($settings['link_options']) ? $settings['link_options'] : [];
            $link = $contact_tools::createModalLink($settings['link_title'], $settings['contact_form'], $link_options, $url_options);
            $replace = render($link);
            break;

          case 'modalLinkAjax':
            $url_options = !empty($settings['url_options']) ? $settings['url_options'] : [];
            $link_options = !empty($settings['link_options']) ? $settings['link_options'] : [];
            $link = $contact_tools::createModalLinkAjax($settings['link_title'], $settings['contact_form'], $link_options, $url_options);
            $replace = render($link);
            break;

          case 'getFormAjax':
            $form = $contact_tools::getFormAjax($settings['contact_form']);
            $replace = render($form);
            break;
        }

        $text = str_replace($element, $replace, $text);
      }
    }

    $result->setProcessedText($text);
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    return $this->t('[contact]{"type": "modalLink", "form": "feedback", "title": "Test"}[/contact] will be replaced with link to contact form opened in modal.');
  }

}
