( function( $ ) {
	"use strict";

	QuickPay.prototype.init = function() {
		// Add event handlers
		this.actionBox.on( 'click', '[data-action]', $.proxy( this.callAction, this ) );
	};

	QuickPay.prototype.callAction = function( e ) {
		e.preventDefault();
		var target = $( e.target );
		var action = target.attr( 'data-action' );

		if( typeof this[action] !== 'undefined' ) {
			var message = target.attr('data-confirm') || 'Are you sure you want to continue?';
			if( confirm( message ) ) {
				this[action]();
			}
		}
	};

	QuickPay.prototype.capture = function() {
		var request = this.request( {
			quickpay_action : 'capture'
		} );
	};

	QuickPay.prototype.captureAmount = function () {
		var request = this.request({
			quickpay_action: 'capture',
			quickpay_amount: $('#qp-balance__amount-field').val()
		} );
	};

	QuickPay.prototype.cancel = function() {
		var request = this.request( {
			quickpay_action : 'cancel'
		} );
	};

	QuickPay.prototype.refund = function() {
		var request = this.request( {
			quickpay_action : 'refund'
		} );
	};

	QuickPay.prototype.split_capture = function() {
		var request = this.request( {
			quickpay_action : 'splitcapture',
			amount : parseFloat( $('#quickpay_split_amount').val() ),
			finalize : 0
		} );
	};

	QuickPay.prototype.split_finalize = function() {
		var request = this.request( {
			quickpay_action : 'splitcapture',
			amount : parseFloat( $('#quickpay_split_amount').val() ),
			finalize : 1
		} );
	};

	QuickPay.prototype.request = function( dataObject ) {
		var that = this;
		var request = $.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType: 'json',
			data : $.extend( {}, { action : 'quickpay_manual_transaction_actions', post : this.postID.val() }, dataObject ),
			beforeSend : $.proxy( this.showLoader, this, true ),
			success : function() {
				$.get( window.location.href, function( data ) {
					var newData = $(data).find( '#' + that.actionBox.attr( 'id' ) + ' .inside' ).html();
					that.actionBox.find( '.inside' ).html( newData );
					that.showLoader( false );
				} );
			},
			error : function(jqXHR, textStatus, errorThrown) {
				alert(jqXHR.responseText);
				that.showLoader( false );
			}
		} );

		return request;
	};

	QuickPay.prototype.showLoader = function( e, show ) {
		if( show ) {
			this.actionBox.append( this.loaderBox );
		} else {
			this.actionBox.find( this.loaderBox ).remove();
		}
	};

    QuickPayCheckAPIStatus.prototype.init = function () {
    	if (this.apiSettingsField.length) {
			$(window).on('load', $.proxy(this.pingAPI, this));
			this.apiSettingsField.on('blur', $.proxy(this.pingAPI, this));
			this.insertIndicator();
		}
	};

	QuickPayCheckAPIStatus.prototype.insertIndicator = function () {
		this.indicator.insertAfter(this.apiSettingsField.hide().fadeIn());
	};

	QuickPayCheckAPIStatus.prototype.pingAPI = function () {
		$.post(ajaxurl, { action: 'quickpay_ping_api', api_key: this.apiSettingsField.val() }, $.proxy(function (response) {
			if (response.status === 'success') {
				this.indicator.addClass('ok').removeClass('error');
			} else {
				this.indicator.addClass('error').removeClass('ok');
			}
		}, this), "json");
	};

	// DOM ready
	$(function() {
		new QuickPay().init();
		new QuickPayCheckAPIStatus().init();
		new QuickPayPrivateKey().init();

		function wcqpInsertAjaxResponseMessage(response) {
			if (response.hasOwnProperty('status') && response.status == 'success') {
				var message = $('<div id="message" class="updated"><p>' + response.message + '</p></div>');
				message.hide();
				message.insertBefore($('#wcqp_wiki'));
				message.fadeIn('fast', function () {
					setTimeout(function () {
						message.fadeOut('fast', function () {
							message.remove();
						});
					},5000);
				});
			}
		}

        var emptyLogsButton = $('#wcqp_logs_clear');
        emptyLogsButton.on('click', function(e) {
        	e.preventDefault();
        	emptyLogsButton.prop('disabled', true);
        	$.getJSON(ajaxurl, { action: 'quickpay_empty_logs' }, function (response) {
				wcqpInsertAjaxResponseMessage(response);
				emptyLogsButton.prop('disabled', false);
        	});
        });

        var flushCacheButton = $('#wcqp_flush_cache');
		flushCacheButton.on('click', function(e) {
        	e.preventDefault();
			flushCacheButton.prop('disabled', true);
        	$.getJSON(ajaxurl, { action: 'quickpay_flush_cache' }, function (response) {
				wcqpInsertAjaxResponseMessage(response);
				flushCacheButton.prop('disabled', false);
        	});
        });
	});

	function QuickPay() {
		this.actionBox 	= $( '#quickpay-payment-actions' );
		this.postID		= $( '#post_ID' );
		this.loaderBox 	= $( '<div class="loader"></div>');
	}

    function QuickPayCheckAPIStatus() {
    	this.apiSettingsField = $('#woocommerce_quickpay_quickpay_apikey');
		this.indicator = $('<span class="wcqp_api_indicator"></span>');
	}

	function QuickPayPrivateKey() {
		this.field = $('#woocommerce_quickpay_quickpay_privatekey');
		this.apiKeyField = $('#woocommerce_quickpay_quickpay_apikey');
		this.refresh = $('<span class="wcqp_api_indicator refresh"></span>');
	}

	QuickPayPrivateKey.prototype.init = function () {
		var self = this;
		this.field.parent().append(this.refresh.hide());

		this.refresh.on('click', function() {
			if ( ! self.refresh.hasClass('ok')) {
				self.refresh.addClass('is-loading');
				$.post(ajaxurl + '?action=quickpay_fetch_private_key', { api_key: self.apiKeyField.val() }, function(response) {
					if (response.status === 'success') {
						self.field.val(response.data.private_key);
						self.refresh.removeClass('refresh').addClass('ok');
					} else {
						self.flashError(response.message);
					}

					self.refresh.removeClass('is-loading');
				}, 'json');
			}
		});

		this.validatePrivateKey();
	}

	QuickPayPrivateKey.prototype.validatePrivateKey = function() {
		var self = this;
		$.post(ajaxurl + '?action=quickpay_fetch_private_key', { api_key: self.apiKeyField.val() }, function(response) {
			if (response.status === 'success' && self.field.val() === response.data.private_key) {
				self.refresh.removeClass('refresh').addClass('ok');
			}

			self.refresh.fadeIn();
		}, 'json');
	};

	QuickPayPrivateKey.prototype.flashError = function (message) {
		var message = $('<div style="color: red; font-style: italic;"><p style="font-size: 12px;">' + message + '</p></div>');
		message.hide().insertAfter(this.refresh).fadeIn('fast', function() {
			setTimeout(function () {
				message.fadeOut('fast', function() {
					message.remove();
				})
			}, 10000)
		});
	}
})(jQuery);
