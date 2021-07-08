jQuery(document).ready(function($) {
	let shipping_agent_definitions = ShipmondoAdminParams.shipping_agents;

	let shipping_agent = $('select[name="woocommerce_shipmondo_shipping_agent"]');
	let shipping_product = $('select[name="woocommerce_shipmondo_shipping_product"]');
	let shipping_product_options = [];
	shipping_product.find('option').each(function() {
		shipping_product_options[$(this).val()] = $(this).text();
	});
	let shipping_products_row = shipping_product.parents('tr');

	shipping_agent.on('change', function() {
		populateShippingProducts(shipping_agent.val());
	});

	function populateShippingProducts(agent) {
		if(typeof shipping_agent_definitions[agent] !== 'undefined' && typeof shipping_agent_definitions[agent].products !== 'undefined' && !$.isEmptyObject(shipping_agent_definitions[agent].products)) {
			let val = shipping_product.val();
			shipping_product.empty();

			$.each(shipping_agent_definitions[agent].products, function(key, entry) {

				let option = shipping_product_options[key];

				if(typeof option !== 'undefined' && entry == true) {
					shipping_product.append($('<option></option>').attr('value', key).text(option));
					if(key === val) {
						shipping_product.val(val);
					}
				}
			});
			shipping_products_row.show();
			return;
		}

		shipping_products_row.hide();
		shipping_product.empty();

	}

	if(typeof shipping_agent !== 'undefined') {
		populateShippingProducts(shipping_agent.val());
	}

});



if (typeof ShipmondoAdminParams !== 'undefined') {
	var ajax_admin_url = ShipmondoAdminParams.ajax_url;
	var sWeightTranslation = ShipmondoAdminParams.sWeightTranslation;
	var sPriceTranslation = ShipmondoAdminParams.sPriceTranslation;
	var sQuantityTranslation = ShipmondoAdminParams.sQuantityTranslation;
	var sTitleTranslation = ShipmondoAdminParams.sTitleTranslation;
	var sMinimumTranslation = ShipmondoAdminParams.sMinimumTranslation;
	var sMaximumTranslation = ShipmondoAdminParams.sMaximumTranslation;
	var sShippingPriceTranslation = ShipmondoAdminParams.sShippingPriceTranslation;
	var sBtnAddNewPriceRangeRowTranslation = ShipmondoAdminParams.sBtnAddNewPriceRangeRowTranslation;
	var sCartTotalTranslation = ShipmondoAdminParams.sCartTotalTranslation;
	var sCurrencySymbol = ShipmondoAdminParams.sCurrencySymbol;
	var sWeightUnit = ShipmondoAdminParams.sWeightUnit;
	var sShippingRangeHelperTextTranslation = ShipmondoAdminParams.sShippingRangeHelperTextTranslation;



	jQuery(document).ready(function()
	{

		if(jQuery.urlParam('page') === "wc-settings" && jQuery.urlParam('tab') === "shipping")
		{
			addHtmlAfterTarget('.shipping_price', 'Weight', sWeightUnit);
			addHtmlAfterTarget('.shipping_price', 'Price', sCurrencySymbol);

			/****************
			 *  Executed on page load
			 ****************/
			if (jQuery('.differentiated_price_type').val() === 'Weight')
			{
				jQuery('.shipping_price').parents().eq(2).hide();
				jQuery('.Price_tr').hide();
				jQuery('.Weight_tr').show();
			}
			else if (jQuery('.differentiated_price_type').val() === 'Price')
			{
				jQuery('.shipping_price').parents().eq(2).hide();
				jQuery('.Weight_tr').hide();
				jQuery('.Price_tr').show();
			}

			if(jQuery('.enable_free_shipping').val() === "No")
			{
				jQuery('.free_shipping_total').parents().eq(2).hide();
			}
			else
			{
				jQuery('.free_shipping_total').parents().eq(2).show();
			}



			sRangeType = jQuery('.differentiated_price_type').val();
			var aShippingData = {
				'action': 'shipmondo_get_price_ranges',
				'iInstance_id': jQuery.urlParam('instance_id'),
				'sRangeType' : sRangeType
			};
			jQuery.post(ajax_admin_url, aShippingData, function(response)
			{
				if (response)
				{
					var returned = JSON.parse(response);
					if (returned.status === "success")
					{
						if(returned.oData !== null && returned.oData !== "undefined")
						{
							if(sRangeType === "Price")
							{
								sUnit = sCurrencySymbol;
							}
							else if (sRangeType === "Weight")
							{
								sUnit = sWeightUnit;
							}
							jQuery('.' + sRangeType + '_div > table > tbody > .shipmondo_tr').remove();
							for(oRow in returned.oData)
							{
								//+
								//console.log(jQuery('.' + jQuery('#woocommerce_shipmondo_shipping_gls_private_differentiated_price_type').val() + '_div > table > tbody'));
								jQuery('.' + sRangeType + '_div > table > tbody').append(RowToTablehtml(returned.oData[oRow]['minimum'], returned.oData[oRow]['maximum'], returned.oData[oRow]['shipping_price'], sUnit));
							}
						}
					}
					else if (returned.status === "error")
					{
					}
				}
			});


			/****************
			 *  On('change')
			 ****************/
			jQuery('.differentiated_price_type').on('change', function()
			{
				if(jQuery('.differentiated_price_type').val() === "Quantity")
				{
					jQuery('.Weight_tr').hide();
					jQuery('.Price_tr').hide();
					jQuery('.shipping_price').parents().eq(2).show();
				}
				else if(jQuery('.differentiated_price_type').val() === 'Weight')
				{
					jQuery('.shipping_price').parents().eq(2).hide();
					jQuery('.Price_tr').hide();
					jQuery('.Weight_tr').show();
				}
				else if(jQuery('.differentiated_price_type').val() === 'Price')
				{
					jQuery('.shipping_price').parents().eq(2).hide();
					jQuery('.Weight_tr').hide();
					jQuery('.Price_tr').show();
				}
			});

			jQuery('.differentiated_price_type').on('change', function()
			{
				sRangeType = jQuery('.differentiated_price_type').val();
				var aShippingData = {
					'action': 'shipmondo_get_price_ranges',
					'iInstance_id': jQuery.urlParam('instance_id'),
					'sRangeType' : sRangeType
				};


				jQuery.post(ajax_admin_url, aShippingData, function(response)
				{
					if (response)
					{
						var returned = JSON.parse(response);
						if (returned.status === "success")
						{
							if(returned.oData.oRows !== null && returned.oData.oRows !== "undefined")
							{
								if(sRangeType === "Price")
								{
									sUnit = sCurrencySymbol;
								}
								else if (sRangeType === "Weight")
								{
									sUnit = sWeightUnit;
								}
								jQuery('.' + sRangeType + '_div > table > tbody > .shipmondo_tr').remove();
								for(oRow in returned.oData.oRows)
								{
									//console.log(jQuery('.' + jQuery('#woocommerce_shipmondo_shipping_gls_private_differentiated_price_type').val() + '_div > table > tbody'));
									jQuery('.' + sRangeType + '_div > table > tbody').append(RowToTablehtml(returned.oData.oRows[oRow]['minimum'], returned.oData.oRows[oRow]['maximum'], returned.oData.oRows[oRow]['shipping_price'], sUnit));
								}
							}
						}
						else if (returned.status === "error")
						{
							//console.log("error");
						}
					}
				});
			});




			/****************
			 *  On('click')
			 ****************/
			jQuery('.button-add-new-price-range-row').on('click', function()
			{
				sRangeType = jQuery('.differentiated_price_type').val();
				//  console.log(sRangeType);
				if(sRangeType === "Price")
				{
					sUnit = sCurrencySymbol;
				}
				else if (sRangeType === "Weight")
				{
					sUnit = sWeightUnit;
				}
				else
				{
					sUnit = "";
				}

				// console.log(sUnit);
				jQuery(this).parent().siblings('.shipmondo_table').append(RowToTablehtml("", "", "", sUnit));
			});

			jQuery(document).on('click', '.button-delete-row' , function()
			{
				if(jQuery(this).parent().parent().parent().children('.shipmondo_tr').length > 1)
				{
					jQuery(this).parents().eq(1).remove();
				}
			});

			jQuery(document).on('change', '.enable_free_shipping', function()
			{
				// console.log(jQuery('.enable_free_shipping').val());
				if(jQuery('.enable_free_shipping').val() === "No")
				{
					jQuery('.free_shipping_total').parents().eq(2).hide();
				}
				else
				{
					jQuery('.free_shipping_total').parents().eq(2).show();
				}
			});





			jQuery(document).on('change', '.shipmondo_input_shipping_price, .shipmondo_input_minimum, .shipmondo_input_maximum',  function()
			{
				iMinimumPrice = jQuery(this).parent().parent().find('.shipmondo_input_minimum').val();
				iMaximumPrice= jQuery(this).parent().parent().find('.shipmondo_input_maximum').val();
				iShippingPrice = jQuery(this).parent().parent().find('.shipmondo_input_shipping_price').val();
				var bErrors = false;

				if(iShippingPrice)
				{
					if(!isNumeric(iShippingPrice) || parseInt(iShippingPrice) < 0)
					{
						jQuery(this).parent().parent().addClass('error-range-row');
						bErrors = true;
					}
				}
				if(iMaximumPrice && iMinimumPrice)
				{
					if(parseFloat(iMinimumPrice) >= parseFloat(iMaximumPrice) || !isNumeric(iMinimumPrice) || !isNumeric(iMaximumPrice))
					{
						jQuery(this).parent().parent().addClass('error-range-row');
						bErrors = true;
					}
				}
				if(!bErrors)
				{
					jQuery(this).parent().parent().removeClass('error-range-row');
				}
			})


			jQuery('.woocommerce-save-button').on('click', function(event)
			{
				i = 1;
				shipmondo_price_rows = jQuery('.shipmondo_tr:visible');
				jQuery('.shipmondo_input_maximum:visible, .shipmondo_input_minimum:visible, .shipmondo_input_shipping_price:visible').each(function()
				{
					if(!isNumeric(jQuery(this).val()))
					{
						jQuery(this).parent().parent().addClass('error-range-row');
					}
					if(jQuery(this).parent().parent().hasClass('error-range-row'))
					{
						event.preventDefault();
						return;
					}
				});


				oShippingRows = '{"oRows":[';
				jQuery(shipmondo_price_rows).each(function()
				{
					minimum_range = jQuery(this).find('.shipmondo_td_minimum > input').val();
					maximum_range = jQuery(this).find('.shipmondo_td_maximum > input').val();
					shipping_price_range = jQuery(this).find('.shipmondo_td_shipping_price > input').val();
					oShippingRows += '{"minimum": "'+minimum_range + '", "maximum": "'+maximum_range+'", "shipping_price": "'+shipping_price_range+'"}';
					if(shipmondo_price_rows.length > i )
					{
						oShippingRows += ',';
					}
					i = i+1;
				});
				oShippingRows += ']}';

				var data = {
					'sRangeType' : jQuery('.differentiated_price_type').val(),
					'iInstance_id': jQuery.urlParam('instance_id'),
					'oShippingRows' : oShippingRows
				};

				jQuery('.hidden_post_field').val(JSON.stringify(data));
			});

		}
	}); // End on doc ready




	jQuery.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (results==null){
			return null;
		}
		else{
			return results[1] || 0;
		}
	};

	function isNumeric(n)
	{
		return !isNaN(parseFloat(n)) && isFinite(n);
	}


	function RowToTablehtml(minimum, maximum, shipping_price, symbol)
	{
		var inputType = symbol === 'kg' ? 'number' : 'text';

		var range_row_html =
			'<tr class="shipmondo_tr">' +
			'<td class="shipmondo_td_minimum">' +
			'<input class="shipmondo_input_minimum" type="' + inputType + '" placeholder="'+symbol+'" value="'+minimum+'"' + (inputType === 'number' ? ' step="0.01"' : '') + '>' +
			'</td>' +
			'<td clas="shipmondo_unit_td_wrapper">' +
			'<div class="shipmondo_unit_wrapper">' +
			'<div class="shipmondo_minimum_sign_wrapper"><</div><div class="shipmondo_cart_total_wrapper">' + sCartTotalTranslation + '</div><div class="shipmondo_maximum_sign_wrapper"><=</div>' +
			'</div>' +
			'</td>' +

			'<td class="shipmondo_td_maximum">' +
			'<input class="shipmondo_input_maximum" type="' + inputType + '" placeholder="'+symbol+'" value="'+maximum+'"' + (inputType === 'number' ? ' step="0.01"' : '') + '>' +
			'</td>' +
			'<td class="shipmondo_td_shipping_price">' +
			'<input class="shipmondo_input_shipping_price" type="text" placeholder="'+sShippingPriceTranslation+'" value="'+shipping_price+'">' +
			'</td>' +
			'<td class="shipmondo_td_delete_row">' +
			'<input class="button-secondary button-delete-row" value="x" type="button">' +
			'</td>' +
			'</tr>';
		return range_row_html;
	}

	function addHtmlAfterTarget(target, html_class, symbol)
	{
		if(html_class === "Price")
		{
			th_text = sPriceTranslation;
		}
		else
		{
			th_text = sWeightTranslation;
		}

		var tr_html =

			'<tr style="display: none;" class="' + html_class + '_tr" valign="top">' +

			'<th></th>' +
			'<td class="' + html_class + '_td">' +
			//'<div>' + sTitleTranslation + ' ' +th_text+'</div>' +
			'<div class="'+html_class+'_div">' +
			'<table class="'+html_class+'_shipmondo_table shipmondo_table">' +
			'<tr>' +
			'<th>' +
			sMinimumTranslation + ' ' + th_text + ' (' + symbol + ')' +
			'</th>' +
			'<th>' +
			'</th>' +
			'<th>' +
			sMaximumTranslation + ' ' + th_text + ' (' + symbol + ')' +
			'</th>' +
			'<th>' +
			sShippingPriceTranslation +
			'</th>' +
			'</tr>' +
			RowToTablehtml("", "", "", symbol) +
			'</table>' +
			'<div class="save_and_addnew_wrapper">' +
			'<input class="shipmondo_range_type" type="hidden" value="' + html_class + '" ></input>' +
			'<input class="button-secondary button-add-new-price-range-row" value="'+ sBtnAddNewPriceRangeRowTranslation +'" type="button">' +
			'</div>'+
			'</div>' +
			'</td>' +
			'</tr>';
		jQuery(tr_html).insertAfter(jQuery(target).parents().eq(2));
		//      jQuery('.' + sRangeType + '_div > table > tbody').append()
		jQuery('.'+html_class+'_td').prepend('<div class="shipmondo_range_helper_text">' + sShippingRangeHelperTextTranslation + '</div>');
	}
}