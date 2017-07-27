/**
 * @file
 * Converts Facets options as AJAX checkboxes.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.ajax_facets_checkboxes = Drupal.ajax_facets_checkboxes || { viewDomId: null, viewSettings: null };
  Drupal.behaviors.facetsAjaxCheckboxesWidget = {
    attach: function (context, drupalSettings) {
      Drupal.ajax_facets_checkboxes.makeAjaxCheckboxesFacet(context, drupalSettings);
    }
  };

  /**
   * Replace a link with a checked checkbox.
   * 
   * @param {object} context
   *   Context.
   * @param {object} settings
   *   Settings.
   */
  Drupal.ajax_facets_checkboxes.makeAjaxCheckboxesFacet = function (context, settings) {
    $('.js-facets-ajax-checkboxes').once('facets-checkboxes-ajaxify').each(function () {
      var $ul = $(this);
      var $links = $ul.find('.facet-item input[type="checkbox"]');
      $links.each(function () {
        var $option = $(this);
        var active = $option.hasClass('is-active');
        var description = $option.html();
        var href = $option.attr('href');
        var id = $option.data('drupal-facet-item-id');
        var checkbox = $('<input type="checkbox" class="facets-checkbox" id="' + id + '" data-facet-query="' + $(this).attr('data-facet-query') + '" />');
        var label = $('<label for="' + id + '">' + description + '</label>');
        $option.on('change', function (event) {
          $(this).toggleClass('active');
          var facet_query = [];
          $('.js-facets-ajax-checkboxes .active').each(function(){
            facet_query.push($(this).attr('data-facet-query'));
          });
          Drupal.ajax_facets.ajaxView(null, facet_query);
          //event.preventDefault();
        });
        //$option.before(checkbox).before(label).hide();
      });
    });
  }

})(jQuery, Drupal, drupalSettings);
 
 
