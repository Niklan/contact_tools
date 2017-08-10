<?php

namespace Drupal\contact_tools\Controller;

use Drupal\Component\Utility\Html;
use Drupal\contact\ContactFormInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller routines for contact routes.
 */
class ContactToolsPageController extends ControllerBase {

  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function contactPageAjax(ContactFormInterface $contact_form = NULL) {
    $config = $this->config('contact.settings');
    $query = \Drupal::request()->query;

    // Use the default form if no form has been passed.
    if (empty($contact_form)) {
      $contact_form = $this->entityManager()
        ->getStorage('contact_form')
        ->load($config->get('default_form'));
      // If there are no forms, do not display the form.
      if (empty($contact_form)) {
        if ($this->currentUser()->hasPermission('administer contact forms')) {
          drupal_set_message($this->t('The contact form has not been configured. <a href=":add">Add one or more forms</a> .', [
            ':add' => $this->url('contact.form_add')]), 'error');
          return [];
        }
        else {
          throw new NotFoundHttpException();
        }
      }
    }

    $message = $this->entityManager()
      ->getStorage('contact_message')
      ->create([
        'contact_form' => $contact_form->id(),
      ]);

    // Ajax is added by hook_form_alter(). Because here we can't change any of
    // actions of the form.
    $form_state_additional = [
      'contact_tools' => [
        'is_ajax' => TRUE,
      ],
    ];
    $title = $contact_form->label();
    if ($query->get('modal-title') && is_string($query->get('modal-title'))) {
      $title = $query->get('modal-title');
    }
    $form = $this->entityFormBuilder()->getForm($message, 'default', $form_state_additional);
    $form['#title'] = $title;
    $form['#cache']['contexts'][] = 'user.permissions';
    $this->renderer->addCacheableDependency($form, $config);
    return $form;
  }

}
