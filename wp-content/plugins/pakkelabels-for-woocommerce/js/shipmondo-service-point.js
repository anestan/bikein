jQuery(document).ready(function($) {
    // The body
    var body = $('body');
    // The zipcode fields (selector)
    var zipcode_fields = 'input.shipmondo_zipcode';
    // The edited zipcode fields
    var zipcode_fields_edited = [];
    // WooCommerce input fields
    var woo_shipping_postcode = $('input[name="shipping_postcode"]');
    var woo_shipping_address_active = $('input[name="ship_to_different_address"]');
    var woo_billing_postcode = $('input[name="billing_postcode"]');
    // Pickup Points HTML
    var service_points_html = [];
    // Selected Shops
    var selected_shops = [];

    // Check if field has been edited
    function hasZipcodeBeenEdited(field) {
        var index = getShippingIndex(field);
        if(typeof zipcode_fields_edited[index] !== "undefined") {
            return zipcode_fields_edited[index];
        }
        return false;
    }

    // Check if zipcode is valud
    function isZipcodeValid(zipcode) {
        if(typeof zipcode === "undefined") {
            return false;
        }
        return zipcode.length > 0;
    }

    // Update zipcode fields based on order details
    function updateZipcodeFields() {
        var zip_fields = $(zipcode_fields);
        if(zip_fields.length < 1) {
            return;
        }

        zip_fields.each(function(index) {
            if(!hasZipcodeBeenEdited($(this))) {
                if(woo_shipping_address_active.is(':checked') && isZipcodeValid(woo_shipping_postcode.val())) {
                    $(this).val(woo_shipping_postcode.val()).trigger('shipmondo_zipcode_field_updated');
                } else if(isZipcodeValid(woo_billing_postcode.val())) {
                    $(this).val(woo_billing_postcode.val()).trigger('shipmondo_zipcode_field_updated');
                }
            } else {
                $(this).val(hasZipcodeBeenEdited($(this)));
            }
        });
    }

    // Get parent wrapper
    function getWrapper(element) {
        return element.parents('.shipmondo-shipping-field-wrap');
    }

    // get the shipping index from the parent
    function getShippingIndex(element) {
        return getWrapper(element).data('shipping_index');
    }

    // get the shipping index from the parent
    function getShippingAgent(element) {
        return getWrapper(element).data('shipping_agent');
    }

    // Update edited zipcode fields
    $(document).on('input', zipcode_fields, function () {
        var index = getShippingIndex($(this));
        if(isZipcodeValid($(this).val())){
            zipcode_fields_edited[index] = $(this).val();
        } else {
            delete zipcode_fields_edited[index];
        }
    });

    // Update fields after load
    $(document).on('shipmondo_zipcode_field_loaded', function() {
        updateZipcodeFields();
        // set shop if selected
        $('.shipmondo-shipping-field-wrap').each(function() {
            var index = $(this).data('shipping_index');
            var agent = $(this).data('shipping_agent');

            if(typeof selected_shops[index] !== 'undefined' &&
               typeof selected_shops[index][agent] !== 'undefined') {
                setShopHTML(index, selected_shops[index][agent], $(this));
            }
        });
    });

    // Button enabling and error text removal
    $(document).on('shipmondo_zipcode_field_updated', zipcode_fields, function() {
        var wrapper = getWrapper($(this));
        var button = wrapper.find('.shipmondo_select_button');
        var error_text = wrapper.find('.shipmondo_zipcode_error_text');
        if(isZipcodeValid($(this).val())) {
            button.prop("disabled", false);
            error_text.removeClass('active');
        } else {
            button.prop("disabled", true);
            error_text.addClass('active');
        }
    });

    // Trigger zipcode field update
    $(document).on('keyup focusout input change', zipcode_fields, function () {
        $(this).trigger('shipmondo_zipcode_field_updated');
    });

    // Listeners on WooCommerce inputs
    woo_shipping_postcode.on('keyup focusout input change', function() {
        updateZipcodeFields();
    });
    woo_shipping_address_active.on('change', function() {
        updateZipcodeFields();
    });
    woo_billing_postcode.on('keyup focusout input change', function() {
        updateZipcodeFields();
    });

    // Get pickup points HTML
    function getServicePoints(agent, zipcode, display_type, element, callback) {
        if(woo_shipping_address_active.is(':checked')) {
            var country = $('[name="shipping_country"]').val();
        } else {
            var country = $('[name="billing_country"]').val();
        }
        // Check if we already got the correct data
        if(typeof service_points_html[agent] !== 'undefined' &&
           typeof service_points_html[agent][country] !== 'undefined' &&
           typeof service_points_html[agent][country][zipcode] !== 'undefined' &&
           typeof service_points_html[agent][country][zipcode][display_type] !== 'undefined') {
            return callback(element, service_points_html[agent][country][zipcode][display_type]);
        }

        // Get pickup points data
        $.post(shipmondo.ajax_url, {
            'action': 'shipmondo_get_service_points',
            'selection_type': display_type,
            'agent': agent,
            'zipcode': zipcode,
            'country': country
        }, function(result) {
            if (result.success === false) {
                return callback(element, result.data.html);
            }

            if(typeof service_points_html[agent] === 'undefined') {
                service_points_html[agent] = [];
            }
            if(typeof service_points_html[agent][country] === 'undefined') {
                service_points_html[agent][country] = [];
            }
            if(typeof service_points_html[agent][country][zipcode] === 'undefined') {
                service_points_html[agent][country][zipcode] = [];
            }
            service_points_html[agent][country][zipcode][display_type] = result;

            return callback(element, result);
        }).fail(function() {
            return callback(element, false);
        });
    }

    // Select shop
    function shopSelected(shop_element, element) {
        var index = getShippingIndex(element);
        var agent = getShippingAgent(element);

        var shop = {
            'id': $(shop_element).attr('data-id'),
            'name': $('.input_shop_name', shop_element).val(),
            'address': $('.input_shop_address', shop_element).val(),
            'zip': $('.input_shop_zip', shop_element).val(),
            'city': $('.input_shop_city', shop_element).val(),
            'id_string': $('.input_shop_id', shop_element).val()
        };

        if(typeof selected_shops[index] !== 'undefined' &&
           typeof selected_shops[index][agent] !== 'undefined' &&
           selected_shops[index][agent] === shop) {
            return;
        }

        if(typeof selected_shops[index] === 'undefined') {
            selected_shops[index] = [];
        }

        selected_shops[index][agent] = shop;

        $('.shipmondo-shop-list.selected').removeClass('selected');
        $(shop_element).addClass('selected');

        setSelectionSession(shop, agent, index);

        setShopHTML(index, shop, getWrapper(element));
    }

    // Set HTML and input fields
    function setShopHTML(index, shop, wrapper) {
        wrapper.find('input[name="shipmondo[' + index + ']"]').val(shop.id);
        wrapper.find('input[name="shop_name[' + index + ']"]').val(shop.name);
        wrapper.find('input[name="shop_address[' + index + ']"]').val(shop.address);
        wrapper.find('input[name="shop_zip[' + index + ']"]').val(shop.zip);
        wrapper.find('input[name="shop_city[' + index + ']"]').val(shop.city);
        wrapper.find('input[name="shop_ID[' + index + ']"]').val(shop.id_string);

        wrapper.find('.shipmondo-shop-name').html(shop.name);
        wrapper.find('.shipmondo-shop-address').html(shop.address);
        wrapper.find('.shipmondo-shop-zip-and-city').html(shop.zip + ', ' + shop.city);

        wrapper.find('.selected_shop_context').addClass('active');
    }

    // Set shop session
    function setSelectionSession(shop, agent, index) {
        $.post(shipmondo.ajax_url,
            {
                'action': 'shipmondo_set_selection_session',
                'selection': shop,
                'agent': agent,
                'shipping_index': index,
            }, function(result) {

            });
    }

    // Allow enter in zipcode field
    $(document).on('keypress', zipcode_fields, function(e) {
        if(e.keyCode === 13) {
            e.preventDefault();
            if(isZipcodeValid($(this).val())){
                getWrapper($(this)).find('.shipmondo_select_button').click();
                $(this).blur();
            }
        }
    });

    // MODAL
    // Modal selectors
    var modal = $('.shipmondo-modal');
    var modal_content = modal.find('.shipmondo-removable-content');
    var modal_error = modal.find('.shipmondo-error');
    var modal_close_button = '.shipmondo-modal-close-button, .shipmondo-modal-close';
    var current_modal_element = null;
    var service_points_json = 'input[name="shipmondo_service_points_json"]';
    var map = null;
    var bounds = null;
    var infowindow;

    // Open modal
    $(document).on('click', '.shipmondo_select_button[data-selection-type="modal"]', function(e) {
        e.stopPropagation();
        getServicePointsModal($(this));
    });

    // Open modal and get the pickup points
    function getServicePointsModal(element) {
        modal.removeClass('shipmondo-hidden');

        setTimeout(function() {
            body.addClass('shipmondo-modal-open');
            modal.addClass('visible');
        }, 100);

        modal.addClass('loading');

        modal_content.empty();

        var wrapper = getWrapper(element);

        var agent = getShippingAgent(element);
        var zipcode = wrapper.find('.shipmondo_zipcode').val();

        current_modal_element = element;

        getServicePoints(agent, zipcode, 'modal', element, function(element, html) {
            var wrapper = getWrapper(element);
            if(html === false) {
                modal_error.addClass('visible');
            } else {
                modal_content.html(html);
                $('.shipmondo-modal-content').addClass('visible');
            }

            var index = getShippingIndex(element);
            var agent = getShippingAgent(element);

            if(typeof selected_shops[index] !== 'undefined' &&
                typeof selected_shops[index][agent] !== 'undefined') {
                wrapper.find('.shipmondo-shop-list[data-id="' + selected_shops[index][agent].id + '"]').addClass('selected');
            }


            modal.removeClass('loading');
        });
    }

    // Hide modal
    function hideModal() {
        modal.removeClass('visible').removeClass('loading');
        setTimeout(function() {
            $('.shipmondo-modal-content').addClass('visible');
            $('.shipmondo-modal-checkmark').removeClass('visible');
            modal.addClass('shipmondo-hidden');
        }, 300);
        modal_error.removeClass('visible');
        if (typeof infowindow !== 'undefined') {
            infowindow.close();
        }
        body.removeClass('shipmondo-modal-open');
    }

    // Render Markers
    function shipmondoLoadMarker(data) {
        var marker = new google.maps.Marker({
            position: {lat: parseFloat(data.latitude), lng: parseFloat(data.longitude)},
            map: map,
            icon: {
                url: shipmondo[data.agent + '_icon_url'],
                size: new google.maps.Size(48, 48),
                scaledSize: new google.maps.Size(48, 48),
                anchor: new google.maps.Point(24, 24)
            }
        });

        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function () {
                infowindow.setContent('<strong>' + data.company_name + '</strong><br/>' + data.address + "<br/> " + data.city + ' <br/> ' + data.zipcode + '<br/><div id="shipmondo-button-wrapper"><button class="button" id="shipmondo-select-shop" data-number="' + data.number + '">' + shipmondo.select_shop_text + '</button></div>');
                infowindow.open(map, marker);
            };
        })(marker));

        bounds.extend(marker.position);
    }

    // Render map
    function shipmondoRenderMap() {
        map = new google.maps.Map(document.getElementById('shipmondo-map'), {
            zoom: 6,
            center: {lat: 55.9150835, lng: 10.4713954},
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false
        });

        infowindow = new google.maps.InfoWindow();

        bounds = new google.maps.LatLngBounds();

        var service_points = JSON.parse($(service_points_json).val());

        $.each(service_points, function(index, element) {
            shipmondoLoadMarker(element);
        });

        setTimeout(function () {
            map.fitBounds(bounds);
        }, 100);
    }

    // Render modal after HTML load
    $(document).on('shipmondo_service_point_modal_loaded', function() {
        shipmondoRenderMap();
    });

    // Hide modal on close button
    $(document).on('click', modal_close_button, function() {
        hideModal();
    });

    // Hide modal when clicking outsite modal content
    modal.on('click', function(e) {
        if(typeof e.target !== 'undefined' && e.target.id === 'shipmondo-modal') {
            hideModal();
        }
    });

    // Select shop
    modal.on('click', '.shipmondo-shop-list', function() {
        shopSelected($(this), current_modal_element);
        $('.shipmondo-modal-content').removeClass('visible');
        $('.shipmondo-modal-checkmark').addClass('visible');

        setTimeout(function() {
            hideModal();
        }, 1800);
    });

    // Select shop
    $(modal).on('click', '#shipmondo-select-shop', function(e) {
        e.preventDefault();
        var shop = $('.shipmondo-shoplist-ul > li[data-id=' + $(this).data('number') + ']');
        shopSelected(shop, current_modal_element);
        $('.shipmondo-modal-content').removeClass('visible');
        $('.shipmondo-modal-checkmark').addClass('visible');

        setTimeout(function() {
            hideModal();
        }, 1800);
    });

    // DROPDOWN
    // Open dropdown
    $(document).on('click', '.shipmondo_select_button[data-selection-type="dropdown"]', function(e) {
        if($(this).parents('.shipmondo_dropdown_button').hasClass('open')) {
            return;
        }
        e.stopPropagation();
        getServicePointsDropdown($(this));
    });

    // Open dropdown and get the pickup points
    function getServicePointsDropdown(element) {
        var wrapper = getWrapper(element);
        var dropdown = wrapper.find('.shipmondo_service_point_selector_dropdown');
        dropdown.removeClass('shipmondo-hidden');
        dropdown.addClass('loading');

        var dropdown_button = wrapper.find('.shipmondo_dropdown_button');
        dropdown_button.addClass('open');

        var dropdown_content = dropdown.find('.shipmondo-removable-content');

        dropdown_content.empty();

        var agent = getShippingAgent(element);
        var zipcode = wrapper.find('.shipmondo_zipcode').val();

        getServicePoints(agent, zipcode, 'dropdown', element, function(element, html) {
            var wrapper = getWrapper(element);
            var dropdown = wrapper.find('.shipmondo_service_point_selector_dropdown');
            var dropdown_error = dropdown.find('.shipmondo-error');
            var dropdown_content = dropdown.find('.shipmondo-removable-content');
            if(html === false) {
                dropdown_error.addClass('visible');
            } else {
                dropdown_content.html(html);
            }

            var index = getShippingIndex(element);
            var agent = getShippingAgent(element);

            if(typeof selected_shops[index] !== 'undefined' &&
               typeof selected_shops[index][agent] !== 'undefined') {
                wrapper.find('.shipmondo-shop-list[data-id="' + selected_shops[index][agent].id + '"]').addClass('selected');
            }

            dropdown.removeClass('loading');
        });
    }

    // Hide dropdown
    function hideDropdown(element) {
        var wrapper = getWrapper(element);
        var dropdown = wrapper.find('.shipmondo_service_point_selector_dropdown');
        var dropdown_button = wrapper.find('.shipmondo_dropdown_button');

        dropdown.removeClass('loading').addClass('shipmondo-hidden');
        dropdown_button.removeClass('open');
    }

    // Hide dropdown when clicked outsite of it
    $(document).on('click', function(e) {
        var dropdown = $('.shipmondo_service_point_selector_dropdown:not(".shipmondo-hidden")');

        if(dropdown.length > 0 && (!dropdown.is(e.target) && dropdown.has(e.target).length === 0)) {
            hideDropdown(dropdown);
        }
    })

    // Set selected shop
    $(document).on('click', '.shipmondo_service_point_selector_dropdown .shipmondo-shop-list', function(e) {
        shopSelected($(this), $(this));
        hideDropdown($(this));
    });


    // initialize
    updateZipcodeFields();
});