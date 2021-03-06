<?php

namespace Drupal\ajax_facets\Plugin\facets\widget;

use Drupal\facets\FacetInterface;
use Drupal\facets\Result\Result;
use Drupal\facets\Result\ResultInterface;
use Drupal\ajax_facets\Widget\AjaxFacetsBaseWidget;

/**
 * The checkbox ajax widget.
 *
 * @FacetsWidget(
 *   id = "ajax_links_widget",
 *   label = @Translation("List of links with ajax update"),
 *   description = @Translation("Display links, with AJAX support."),
 * )
 */
class AjaxLinksWidget extends AjaxFacetsBaseWidget {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    $build['#attributes']['class'][] = 'js-facets-ajax-links';
    $build['#attached']['library'][] = 'ajax_facets/ajax_facets.links_widget';

    return $build;
  }

} 
