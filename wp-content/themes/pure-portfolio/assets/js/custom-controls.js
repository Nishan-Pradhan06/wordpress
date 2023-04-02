jQuery(document).ready(function($) {

    "use strict";
    /**
     * ============================================
     * Sortable Repeater Custom Control
     * ============================================
     */

    // Update the values for all our input fields and initialise the sortable repeater
    $('.sortable_repeater_control').each(function() {
        // If there is an existing customizer value, populate our rows
        var defaultValuesArray = $(this).find('.customize-control-sortable-repeater').val().split(',');
        var numRepeaterItems = defaultValuesArray.length;

        if (numRepeaterItems > 0) {
            // Add the first item to our existing input field
            $(this).find('.repeater-input').val(defaultValuesArray[0]);
            // Create a new row for each new value
            if (numRepeaterItems > 1) {
                var i;
                for (i = 1; i < numRepeaterItems; ++i) {
                    SortableRepeaterAppendRow($(this), defaultValuesArray[i]);
                }
            }
        }
    });

    // Make our Repeater fields sortable
    $(this).find('.sortable_repeater.sortable').sortable({
        update: function(event, ui) {
            SortableRepeaterGetAllInputs($(this).parent());
        }
    });

    // Remove item starting from it's parent element
    $('.sortable_repeater.sortable').on('click', '.customize-control-sortable-repeater-delete', function(event) {
        event.preventDefault();
        var numItems = $(this).parent().parent().find('.repeater').length;

        if (numItems > 1) {
            $(this).parent().slideUp('fast', function() {
                var parentContainer = $(this).parent().parent();
                $(this).remove();
                SortableRepeaterGetAllInputs(parentContainer);
            })
        } else {
            $(this).parent().find('.repeater-input').val('');
            SortableRepeaterGetAllInputs($(this).parent().parent().parent());
        }
    });

    // Add new item
    $('.customize-control-sortable-repeater-add').click(function(event) {
        event.preventDefault();
        SortableRepeaterAppendRow($(this).parent());
        SortableRepeaterGetAllInputs($(this).parent());
    });

    // Refresh our hidden field if any fields change
    $('.sortable_repeater.sortable').change(function() {
        SortableRepeaterGetAllInputs($(this).parent());
    })

    // Add https:// to the start of the URL if it doesn't have it
    $('.sortable_repeater.sortable').on('blur', '.repeater-input', function() {
        var url = $(this);
        var val = url.val();
        if (val && !val.match(/^.+:\/\/.*/)) {
            // Important! Make sure to trigger change event so Customizer knows it has to save the field
        url.val('https://' + val).trigger('change');
    }
});

    // Append a new row to our list of elements
    function SortableRepeaterAppendRow($element, defaultValue = '') {
        var newRow = '<div class="repeater" style="display:none"><input type="text" value="' + defaultValue + '" class="repeater-input" placeholder="https://" /><span class="dashicons dashicons-sort"></span><a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a></div>';

        $element.find('.sortable').append(newRow);
        $element.find('.sortable').find('.repeater:last').slideDown('slow', function() {
            $(this).find('input').focus();
        });
    }

    // Get the values from the repeater input fields and add to our hidden field
    function SortableRepeaterGetAllInputs($element) {
        var inputValues = $element.find('.repeater-input').map(function() {
            return $(this).val();
        }).toArray();
        // Add all the values from our repeater fields to the hidden field (which is the one that actually gets saved)
        $element.find('.customize-control-sortable-repeater').val(inputValues);
        // Important! Make sure to trigger change event so Customizer knows it has to save the field
        $element.find('.customize-control-sortable-repeater').trigger('change');
    }

    /**
     * ============================================
     * Multi Input Custom control
     * ============================================
     */
    function customize_multi_write($element){
        var customize_multi_val = '';
        $element.find('.customize_multi_fields .customize_multi_single_field').each(function(){
            customize_multi_val += $(this).val()+'|';
        });
        $element.find('.customize_multi_value_field').val(customize_multi_val.slice(0, -1)).change();
    }

    function customize_multi_add_field(e){
        e.preventDefault();
        var $control = $(this).parents('.customize_multi_input');
        $control.find('.customize_multi_fields').append('<div class="set"><input type="text" value="" class="customize_multi_single_field" /><a href="#" class="customize_multi_remove_field dashicons dashicons-no-alt">X</a></div>');
    }

    function customize_multi_single_field() {
        var $control = $(this).parents('.customize_multi_input');
        customize_multi_write($control);
    }

    function customize_multi_remove_field(e){
        e.preventDefault();
        var $this = $(this);
        var $control = $this.parents('.customize_multi_input');
        $this.parent().remove();
        customize_multi_write($control);
    }

    $(document).on('click', '.customize_multi_add_field', customize_multi_add_field)
    .on('keyup', '.customize_multi_single_field', customize_multi_single_field)
    .on('click', '.customize_multi_remove_field', customize_multi_remove_field);

    $('.customize_multi_input').each(function(){
        var $this = $(this);
        var multi_saved_value = $this.find('.customize_multi_value_field').val();
        if(multi_saved_value.length>0){
            var multi_saved_values = multi_saved_value.split("|");
            $this.find('.customize_multi_fields').empty();
            $.each(multi_saved_values, function( index, value ) {
                $this.find('.customize_multi_fields').append('<div class="set"><input type="text" value="'+value+'" class="customize_multi_single_field" /><a href="#" class="customize_multi_remove_field dashicons dashicons-no-alt">X</a></div>');
            });
        }
    });
});

(function(api) {

   api.sectionConstructor['pure-portfolio-upsell'] = api.Section.extend({

        // Remove events for this section.
    attachEvents: function() {},

        // Ensure this section is active. Normally, sections without contents aren't visible.
    isContextuallyActive: function() {
        return true;
    }
});

   const pure_portfolio_section_lists = ['banner','skill','experience','gallery','blog','cta'];
   pure_portfolio_section_lists.forEach(pure_portfolio_homepage_scroll);

   function pure_portfolio_homepage_scroll(item, index) {
        // Detect when the front page sections section is expanded (or closed) so we can adjust the preview accordingly.
    item = item.replace(/-/g, '_');
    wp.customize.section('pure_portfolio_' + item + '_section', function(section) {
        section.expanded.bind(function(isExpanding) {
                // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
            wp.customize.previewer.send(item, { expanded: isExpanding });
        });
    });
}
})(wp.customize);