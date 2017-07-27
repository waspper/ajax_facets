<?php

namespace Drupal\ajax_facets\Plugin\facets\widget;

use Drupal\facets\FacetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\facets\Result\Result;
use Drupal\facets\Result\ResultInterface;
use Drupal\ajax_facets\Widget\AjaxBaseWidget;

/**
 * The checkbox ajax widget.
 *
 * @FacetsWidget(
 *   id = "ajax_dropdown_widget",
 *   label = @Translation("Dropdown with ajax update"),
 *   description = @Translation("Display a select list, with AJAX support."),
 * )
 */
class AjaxDropdownWidget extends AjaxBaseWidget {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    $build['#attributes']['class'][] = 'js-facets-ajax-dropdown';
    $build['#attached']['library'][] = 'ajax_facets/ajax_facets.dropdown_widget';

    return $build;
  }

} 
