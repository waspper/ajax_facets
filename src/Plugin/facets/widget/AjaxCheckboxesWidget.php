<?php

namespace Drupal\ajax_facets\Plugin\facets\widget;

use Drupal\facets\FacetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\facets\Plugin\facets\widget\LinksWidget;
use Drupal\facets\Result\Result;
use Drupal\facets\Result\ResultInterface;
use Drupal\ajax_facets\Widget\AjaxBaseWidget;

/**
 * The checkbox ajax widget.
 *
 * @FacetsWidget(
 *   id = "ajax_checkboxes_widget",
 *   label = @Translation("List of checkboxes with ajax update"),
 *   description = @Translation("Display checkboxes, with AJAX support."),
 * )
 */
class AjaxCheckboxesWidget extends AjaxBaseWidget {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    $build['#attributes']['class'][] = 'js-facets-ajax-checkboxes';
    $build['#attached']['library'][] = 'ajax_facets/ajax_facets.checkboxes_widget';

    return $build;
  }

} 
