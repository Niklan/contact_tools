<?php

namespace Drupal\contact_tools\Controller;

use Drupal\contact\ContactFormInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller routines for contact routes.
 */
final class ContactToolsPageController extends ControllerBase {

  /**
   * The request stack.
   */
  protected RequestStack $requestStack;

  /**
   * Contact message storage.
   *
   * @var \Drupal\Core\Entity\ContentEntityStorageInterface
   */
  protected ContentEntityStorageInterface $contactStorage;

  /**
   * Contact form storage.
   *
   * @var \Drupal\Core\Config\Entity\ConfigEntityStorageInterface
   */
  protected ConfigEntityStorageInterface $contactFormStorage;

  /**
   * {@inheritdoc}
   */
  public function contactPageAjax(ContactFormInterface $contact_form = NULL): array {
    $config = $this->config('contact.settings');
    $query = $this->requestStack->getCurrentRequest()->query;

    // Use the default form if no form has been passed.
    if (empty($contact_form)) {
      $contact_form = $this->contactFormStorage->load($config->get('default_form'));
      // If there are no forms, do not display the form.
      if (empty($contact_form)) {
        if ($this->currentUser()->hasPermission('administer contact forms')) {
          $message = $this->t('The contact form has not been configured. <a href=":add">Add one or more forms</a> .',
            [
              ':add' => Url::fromRoute('contact.form_add')->toString(),
            ]);
          $this->messenger()->addError($message);

          return [];
        }
        else {
          throw new NotFoundHttpException();
        }
      }
    }

    $message = $this->contactStorage->create([
      'contact_form' => $contact_form->id(),
    ]);

    // Ajax is added by hook_form_alter(). Because here we can't change any of
    // actions of the form.
    $form_state_additional = [
      'contact_tools' => [
        'is_ajax' => TRUE,
      ],
    ];
    $form = $this->entityFormBuilder()
      ->getForm($message, 'default', $form_state_additional);

    // Handle title.
    $title = $contact_form->label();
    if ($query->get('modal-title') && is_string($query->get('modal-title'))) {
      $title = $query->get('modal-title');
    }
    $form['#title'] = $title;

    $cache = BubbleableMetadata::createFromRenderArray($form);
    $cache->addCacheContexts(['user.permissions']);
    $cache->addCacheableDependency($config);
    $cache->applyTo($form);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    $entity_type_manager = $container->get('entity_type.manager');

    $instance = new self();
    $instance->requestStack = $container->get('request_stack');
    $instance->contactStorage = $entity_type_manager->getStorage('contact_message');
    $instance->contactFormStorage = $entity_type_manager->getStorage('contact_form');

    return $instance;
  }

}

