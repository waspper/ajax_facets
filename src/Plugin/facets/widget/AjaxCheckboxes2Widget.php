<?php

namespace Drupal\ajax_facets\Plugin\facets\widget;

use Drupal\facets\FacetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\facets\Plugin\facets\widget\LinksWidget;
use Drupal\facets\Result\Result;
use Drupal\facets\Result\ResultInterface;
use Drupal\ajax_facets\Widget\AjaxBase2Widget;

/**
 * The checkbox ajax widget.
 *
 * @FacetsWidget(
 *   id = "ajax_checkboxes2_widget",
 *   label = @Translation("List of checkboxes with ajax update (TEST)"),
 *   description = @Translation("Display checkboxes, with AJAX support."),
 * )
 */
class AjaxCheckboxes2Widget extends AjaxBase2Widget {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    $build['#attributes']['class'][] = 'js-facets-ajax-checkboxes';
    $build['#attached']['library'][] = 'ajax_facets/ajax_facets.checkboxes_widget2';
    
    $options = [];
    foreach($build['#items'] as $key => $option) {
     $options[$option['#attributes']['data-facet-query']] = $option['#attributes']['data-facet-query'];
    }
    $build['#theme'] = 'select';
    $build['#options'] = $options;

    return $build;
  }
  
  
  protected function buildListItems($facet, ResultInterface $result) {
    $items = parent::buildListItems($facet, $result);
    //$items['#title'] = $result->getDisplayValue();

    //kint($items);
    return $items;
  }
  
  
  /**
   * Builds a facet result item.
   *
   * @param \Drupal\facets\Result\ResultInterface $result
   *   The result item.
   *
   * @return array
   *   The facet result item as a render array.
   */
  protected function buildResultItem(ResultInterface $result) {
    $count = $result->getCount();
    return [
      '#type' => 'markup',
      '#is_active' => $result->isActive(),
      '#markup' => $result->getDisplayValue() . ' - ' . $count,
      '#default_value' => $result->getRawValue(),
      '#show_count' => $this->getConfiguration()['show_numbers'] && ($count !== NULL),
      '#count' => $count,
    ];
  }


} 
