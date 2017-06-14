<?php

namespace Drupal\contact_tools\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AjaxContactSubmit
 */
class ContactToolsAjaxController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public static function ajaxSubmitHandler(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
    $ajax_response = new AjaxResponse();
    $message = [
      '#theme' => 'status_messages',
      '#message_list' => drupal_get_messages(),
      '#status_headings' => [
        'status' => t('Status message'),
        'error' => t('Error message'),
        'warning' => t('Warning message'),
      ],
    ];
    $messages = \Drupal::service('renderer')->render($message);
    $ajax_response->addCommand(new HtmlCommand('#' . Html::getClass($form['#form_id']) . '-system-messages', $messages));
    return $ajax_response;
  }

}
