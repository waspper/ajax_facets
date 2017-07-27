<?php

namespace Drupal\ajax_facets\Widget;

use Drupal\facets\FacetInterface;
use Drupal\facets\Result\Result;
use Drupal\facets\Result\ResultInterface;
use Drupal\facets\Widget\WidgetPluginBase;

use Drupal\Core\Form\FormStateInterface;
/**
 * A base class for ajax widgets that implements most of the boilerplate.
 */
abstract class AjaxBase2Widget extends WidgetPluginBase {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    
    $build['#attributes']['class'][] = 'js-facets-ajax';
    $build['#attached']['library'][] = 'ajax_facets/ajax_facets.ajax_facets_base2';

    $source_id = $facet->getFacetSourceId();
    $source_data = explode(':', $source_id);

    $build['#attributes']['class'][] = 'js-facets-ajax-' . $source_data[1];
    $build['#attached']['drupalSettings'] = [
      'ajaxFacets' => [
        'view_name' => $source_data[1],
      ],
    ];

    return $build;
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state, FacetInterface $facet) {
    $form['use_ajax'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use ajax'),
      '#default_value' => $this->getConfiguration()['use_ajax'],
    ];
    return $form;
  }
  
  /**
   * Builds a renderable array of result items.
   *
   * @param \Drupal\facets\FacetInterface $facet
   *   The facet we need to build.
   * @param \Drupal\facets\Result\ResultInterface $result
   *   A result item.
   *
   * @return array
   *   A renderable array of the result.
   */
  protected function buildListItems($facet, ResultInterface $result) {
    $classes = ['facet-item'];

    $children = $result->getChildren();
    // Check if we need to expand this result.
    if ($children && ($this->facet->getExpandHierarchy() || $result->isActive() || $result->hasActiveChildren())) {

      $child_items = [];
      $classes[] = 'facet-item--expanded';
      foreach ($children as $child) {
        $child_items[] = $this->buildListItems($facet, $child);
      }

      $items['children'] = [
        '#theme' => $this->getFacetItemListThemeHook($facet),
        '#items' => $child_items,
      ];

      if ($result->hasActiveChildren()) {
        $classes[] = 'facet-item--active-trail';
      }

    }
    else {
      if ($children) {
        $classes[] = 'facet-item--collapsed';
      }
    }

    if ($result->isActive()) {
      $items['#attributes'] = ['class' => ['is-active']];
    }

    $items['#attributes']['data-facet-query'] = $this->facet->getUrlAlias() . ':' . $result->getRawValue();
    $items['#wrapper_attributes'] = ['class' => $classes];
    $items['#attributes']['data-drupal-facet-item-id'] = $this->facet->getUrlAlias() . '-' . $result->getRawValue();
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
      '#theme' => 'facets_result_item',
      '#is_active' => $result->isActive(),
      '#value' => $result->getDisplayValue(),
      '#show_count' => $this->getConfiguration()['show_numbers'] && ($count !== NULL),
      '#count' => $count,
      '#attributes' => ['class' => 'ok'],
      '#ajax' => [],
    ];
  }


}

