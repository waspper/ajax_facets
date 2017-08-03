<?php

namespace Drupal\ajax_facets\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\ajax_facets\Ajax\AjaxFacetsReloadCommand;

/**
 * Defines a controller to load a view via AJAX.
 */
class AjaxFacetsController implements ContainerInjectionInterface {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a ViewAjaxController object.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
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
  public function facetView(Request $request) {
    $filter = $request->query->get('filter');
    $facet_id = $request->query->get('facet_id');

    $attachments['drupalSettings'] = [
      'ajaxFacets' => [
        'filter' => $filter,
        'facetID' => $facet_id,
      ],
    ];

    $response = new AjaxResponse();
    $response->addAttachments($attachments);
    $response->addCommand(new AjaxFacetsReloadCommand());

    return $response;
  }

}
