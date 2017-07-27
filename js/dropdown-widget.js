/**
 * @file
 * Converts Facets options as AJAX dropdown.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.ajax_facets_dropdown = Drupal.ajax_facets_dropdown || { viewDomId: null, viewSettings: null };
  Drupal.behaviors.facetsAjaxDropdownWidget = {
    attach: function (context, drupalSettings) {
      Drupal.ajax_facets_dropdown.makeAjaxDropdownFacet(context, drupalSettings);
    }
  };

  /**
   * Turns all facet links into a dropdown with options for every link.
   *
   * @param {object} context
   *   Context.
   * @param {object} settings
   *   Settings.
   */
  Drupal.ajax_facets_dropdown.makeAjaxDropdownFacet = function (context, settings) {
    // Find all dropdown facet links and turn them into an option.
    $('.js-facets-ajax-dropdown').once('facets-dropdown-ajaxify').each(function () {
      var $ul = $(this);
      var $links = $ul.find('.facet-item a');
      var $dropdown = $('<select class="facets-dropdown" />').data($ul.data());

      var id = $(this).data('drupal-facet-id');
      var default_option_label = Drupal.t('- Choose -');
      // Add empty text option first.
      var $default_option = $('<option />')
        .attr('value', '')
        .text(default_option_label);
      $dropdown.append($default_option);

      var has_active = false;
      $links.each(function () {
        var $link = $(this);
        var active = $link.hasClass('is-active');
        var $option = $('<option />')
          .attr('value', $link.attr('data-facet-query'))
          .data($link.data());
        if (active) {
          has_active = true;
          // Set empty text value to this link to unselect facet.
          $default_option.attr('value', $link.attr('href'));

          $option.attr('selected', 'selected');
          $link.find('.js-facet-deactivate').remove();
        }
        $option.html($link.text());
        $dropdown.append($option);
      });

      // Go to the selected option when it's clicked.
      $dropdown.on('change.facets', function () {
        var facet_query = [];
        facet_query.push($(this).val());
        Drupal.ajax_facets.ajaxView(null, facet_query);
      });

      // Append empty text option.
      if (!has_active) {
        $default_option.attr('selected', 'selected');
      }

      // Replace links with dropdown.
      $ul.after($dropdown).remove();
      Drupal.attachBehaviors($dropdown.parent()[0], Drupal.settings);
    });
  };

})(jQuery, Drupal, drupalSettings);
 
 
