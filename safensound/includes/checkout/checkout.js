var submitter = null;
function submitFunction() {
	submitter = 1;
}

var errCSS = {
	'border-color': 'red',
	'border-style': 'solid'
};

function bindAutoFill($el){
	if ($el.attr('type') == 'select-one'){
		var method = 'change';
	}else{
		var method = 'blur';
	}
	
	$el.blur(unsetFocus).focus(setFocus);
	
	if (document.attachEvent){
		$el.get(0).attachEvent('onpropertychange', function (){
			if ($(event.srcElement).data('hasFocus') && $(event.srcElement).data('hasFocus') == 'true') return;
			if ($(event.srcElement).val() != '' && $(event.srcElement).hasClass('required')){
				$(event.srcElement).trigger(method);
			}
		});
	}else{
		$el.get(0).addEventListener('onattrmodified', function (e){
			if ($(e.currentTarget).data('hasFocus') && $(e.currentTarget).data('hasFocus') == 'true') return;
			if ($(e.currentTarget).val() != '' && $(e.currentTarget).hasClass('required')){
				$(e.currentTarget).trigger(method);
			}
		}, false);
	}
}

function setFocus(){
	$(this).data('hasFocus', 'true');
}

function unsetFocus(){
	$(this).data('hasFocus', 'false');
}

var checkout = {
	charset: 'UTF-8',
	pageLinks: {},
	fieldSuccessHTML: '<div style="margin-left:1px;margin-top:1px;float:left;" class="success_icon ui-icon-green ui-icon-circle-check"></div>',
	fieldErrorHTML: '<div style="margin-left:1px;margin-top:1px;float:left;" class="error_icon ui-icon-red ui-icon-circle-close"></div>',
	fieldRequiredHTML: '<div style="margin-left:1px;margin-top:1px;float:left;" class="required_icon ui-icon-red ui-icon-gear"></div>',
	showAjaxLoader: function (){
		if(this.showMessagesPopUp == true)
		{
			$('#ajaxMessages').dialog('open');
		}
		$('#ajaxLoader').show();
	},
	hideAjaxLoader: function (){
		$('#ajaxLoader').hide();
		if(this.showMessagesPopUp == true)
		{
			$('#ajaxMessages').dialog('close');
		}
	},
	showAjaxMessage: function (message){
		$('#checkoutButtonContainer').hide();
		$('#ajaxMessages').show().html('<center>Loading...<br><img src="ext/jQuery/themes/smoothness/images/ajax_load.gif"><br>' + message + '</center>');
	},
	hideAjaxMessage: function (){
		$('#checkoutButtonContainer').show();
		$('#ajaxMessages').hide();
	},
	fieldErrorCheck: function ($element, forceCheck, hideIcon){
		forceCheck = forceCheck || false;
		hideIcon = hideIcon || false;
		var errMsg = this.checkFieldForErrors($element, forceCheck);
		if (hideIcon == false){
			if (errMsg != false){
				this.addIcon($element, 'error', errMsg);
				return true;
			}else{
				this.addIcon($element, 'success', errMsg);
			}
		}else{
			if (errMsg != false){
				return true;
			}
		}
		return false;
	},
	checkFieldForErrors: function ($element, forceCheck){
		var hasError = false;
		if ($element.is(':visible') && ($element.hasClass('required') || forceCheck == true)){
			var errCheck = getFieldErrorCheck($element);
			if (!errCheck.errMsg){
				return false;
			}

			switch($element.attr('type')){
				case 'password':
				if ($element.attr('name') == 'password'){
					if ($element.val().length < errCheck.minLength){
						hasError = true;
					}
				}else{
					if ($element.val() != $(':password[name="password"]', $('#billingAddress')).val() || $element.val().length <= 0){
						hasError = true;
					}
				}
				break;
				case 'radio':
				if ($(':radio[name="' + $element.attr('name') + '"]:checked').size() <= 0){
					hasError = true;
				}
				break;
				case 'checkbox':
				if ($(':checkbox[name="' + $element.attr('name') + '"]:checked').size() <= 0){
					hasError = true;
				}
				break;
				case 'select-one':
				if ($element.val() == ''){
					hasError = true;
				}
				break;
				default:
				if ($element.val().length < errCheck.minLength){
					hasError = true;
				}
				break;
			}
			if (hasError == true){
				return errCheck.errMsg;
			}
		}
		return hasError;
	},
	addIcon: function ($curField, iconType, title){
		title = title || false;
		$('.success_icon, .error_icon, .required_icon', $curField.parent()).hide();
		switch(iconType){
			case 'error':
			if (this.initializing == true){
				this.addRequiredIcon($curField, 'Required');
			}else{
				this.addErrorIcon($curField, title);
			}
			break;
			case 'success':
			this.addSuccessIcon($curField, title);
			break;
			case 'required':
			this.addRequiredIcon($curField, 'Required');
			break;
		}
	},
	addSuccessIcon: function ($curField, title){
		if ($('.success_icon', $curField.parent()).size() <= 0){
			$curField.parent().append(this.fieldSuccessHTML);
		}
		$('.success_icon', $curField.parent()).attr('title', title).show();
	},
	addErrorIcon: function ($curField, title){
		if ($('.error_icon', $curField.parent()).size() <= 0){
			$curField.parent().append(this.fieldErrorHTML);
		}
		$('.error_icon', $curField.parent()).attr('title', title).show();
	},
	addRequiredIcon: function ($curField, title){
		if ($curField.hasClass('required')){
			if ($('.required_icon', $curField.parent()).size() <= 0){
				$curField.parent().append(this.fieldRequiredHTML);
			}
			$('.required_icon', $curField.parent()).attr('title', title).show();
		}
	},
	clickButton: function (elementName){
		if ($(':radio[name="' + elementName + '"]').size() <= 0){
			$('input[name="' + elementName + '"]').trigger('click', true);
		}else{
			$(':radio[name="' + elementName + '"]:checked').trigger('click', true);
		}
	},
	addRowMethods: function($row){
		$row.hover(function (){
			if (!$(this).hasClass('moduleRowSelected')){
				$(this).addClass('moduleRowOver');
			}
		}, function (){
			if (!$(this).hasClass('moduleRowSelected')){
				$(this).removeClass('moduleRowOver');
			}
		}).click(function (){
			if (!$(this).hasClass('moduleRowSelected')){
				var selector = ($(this).hasClass('shippingRow') ? '.shippingRow' : '.paymentRow') + '.moduleRowSelected';
				$(selector).removeClass('moduleRowSelected');
				$(this).removeClass('moduleRowOver').addClass('moduleRowSelected');
				if($(':radio', $(this)).is(':disabled')!==true)
				if (!$(':radio', $(this)).is(':checked')){
					$(':radio', $(this)).attr('checked', 'checked').click();
				}
			}
		});
	},
	queueAjaxRequest: function (options){
		var checkoutClass = this;
		var o = {
			url: options.url,
			cache: options.cache || false,
			dataType: options.dataType || 'html',
			type: options.type || 'GET',
			contentType: options.contentType || 'application/x-www-form-urlencoded; charset=' + this.ajaxCharset,
			data: options.data || false,
			beforeSend: options.beforeSend || function (){
				checkoutClass.showAjaxMessage(options.beforeSendMsg || 'Ajax Operation, Please Wait...');
				checkoutClass.showAjaxLoader();
			},
			complete: function (){
				checkoutClass.hideAjaxMessage();
				if (document.ajaxq.q['orderUpdate'].length <= 0){
					checkoutClass.hideAjaxLoader();
				}
			},
			success: options.success,
			error: function (XMLHttpRequest, textStatus, errorThrown){
				if (XMLHttpRequest.responseText == 'session_expired') document.location = this.pageLinks.shoppingCart;
				alert(options.errorMsg || 'There was an ajax error, please contact IT Web Experts for support.');
			}
		};
		$.ajaxq('orderUpdate', o);
	},
	updateAddressHTML: function (type){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=' + (type == 'shipping' ? 'getShippingAddress' : 'getBillingAddress'),
			type: 'post',
			beforeSendMsg: 'Updating ' + (type == 'shipping' ? 'Shipping' : 'Billing') + ' Address',
			success: function (data){
				$('#' + type + 'Address').html(data);
				if(checkoutClass.showAddressInFields == true)
				{
				  checkoutClass.attachAddressFields();
				  $('*[name="billing_state"]').trigger('change');
				  $('*[name="delivery_state"]').trigger('change');
			    }
			},
			errorMsg: 'There was an error loading your ' + type + ' address, please inform IT Web Experts about this error.'
		});
	},
	attachAddressFields: function(){
		var checkoutClass = this;
		$('input', $('#billingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio'){
				$(this).blur(function (){
					if ($(this).hasClass('required')){
						checkoutClass.fieldErrorCheck($(this));
					}
				});
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this), true, true) == false){
						checkoutClass.addIcon($(this), 'success');
					}else{
						checkoutClass.addIcon($(this), 'required');
					}
				}
			}
		});

		$('input,select[name="billing_country"], ', $('#billingAddress')).each(function (){
			var processFunction = function (){
				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this)) == false){
						if($(this).attr('name')=='billing_country' || $(this).attr('name')=='billing_zipcode' || $(this).attr('name')=='billing_state')
						{
							checkoutClass.processBillingAddress();
						}else
						{
							checkoutClass.processBillingAddress(true);
						}
					}
				}else{
					if($(this).attr('name')=='billing_country' || $(this).attr('name')=='billing_zipcode' || $(this).attr('name')=='billing_state')
					{
						checkoutClass.processBillingAddress();
					}else
					{
						checkoutClass.processBillingAddress(true);
					}
				}
			};
			
			$(this).unbind('blur');
			if ($(this).attr('type') == 'select-one'){
				$(this).change(processFunction);
			}else{
				$(this).blur(processFunction);
			}
			bindAutoFill($(this));
		});
		$('input,select[name="shipping_country"]', $('#shippingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							if($(this).attr('name')=='shipping_country' || $(this).attr('name')=='shipping_zipcode' || $(this).attr('name')=='delivery_state')
							{
								checkoutClass.processShippingAddress();
							}else
							{
								checkoutClass.processShippingAddress(true);
							}
						}else{
							$('#noShippingAddress').show();
							$('#shippingMethods').hide();
						}
					}else{
						if($(this).attr('name')=='shipping_country' || $(this).attr('name')=='shipping_zipcode' || $(this).attr('name')=='delivery_state')
						{
							checkoutClass.processShippingAddress();
						}else
						{
							checkoutClass.processShippingAddress(true);
						}
					}
				};
			
				$(this).blur(processAddressFunction);
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					var icon = 'required';
					if ($(this).val() != '' && checkoutClass.fieldErrorCheck($(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon($(this), icon);
				}
			}
		});

		$('select[name="shipping_country"], select[name="billing_country"]').each(function (){
			var $thisName = $(this).attr('name');
			var fieldType = 'billing';
			if ($thisName == 'shipping_country'){
				fieldType = 'shipping';
			}
			checkoutClass.addCountryAjax($(this), fieldType + '_state', 'stateCol_' + fieldType);
		});
		
		$('*[name="billing_state"], *[name="delivery_state"]').each(function (){
			var processAddressFunction = checkoutClass.processBillingAddress;
			if ($(this).attr('name') == 'delivery_state'){
				processAddressFunction = checkoutClass.processShippingAddress;
			}
			
			var processFunction = function (){
				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this)) == false){
						processAddressFunction.call(checkoutClass);
					}
				}else{
					processAddressFunction.call(checkoutClass);
				}
			}
		
			if ($(this).attr('type') == 'select-one'){
				$(this).change(processFunction);
			}else{
				$(this).blur(processFunction);
			}
			bindAutoFill($(this));
		});
	},
	updateCartView: function (){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=updateCartView',
			type: 'post',
			beforeSendMsg: 'Refreshing Shopping Cart',
			success: function (data){
				if (data == 'none'){
					document.location = checkoutClass.pageLinks.shoppingCart;
				}else{
					$('#shoppingCart').html(data);

					$('.removeFromCart').each(function (){
						checkoutClass.addCartRemoveMethod($(this));
					});
				}
			},
			errorMsg: 'There was an error refreshing the shopping cart, please inform IT Web Experts about this error.'
		});
	},
	updateFinalProductListing: function (){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=getProductsFinal',
			type: 'post',
			beforeSendMsg: 'Refreshing Final Product Listing',
			success: function (data){
				$('.finalProducts').html(data);
			},
			errorMsg: 'There was an error refreshing the final products listing, please inform IT Web Experts about this error.'
		});
	},
	setGV: function (status){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=setGV&cot_gv=' + status,
			type: 'post',
			beforeSendMsg: (status=='on'?'':'Un') + 'Setting Gift Voucher',
			dataType: 'json',
			success: function (data){
				checkoutClass.updateOrderTotals();
			},
			errorMsg: 'There was an error ' + (status=='on'?'':'Un') + 'setting Gift Voucher method, please inform IT Web Experts about this error.'
		});
	},
	updateOrderTotals: function (){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			cache: false,
			data: 'action=getOrderTotals',
			type: 'post',
			beforeSendMsg: 'Updating Order Totals',
			success: function (data){
				$('.orderTotals').html(data);
				checkoutClass.hideAjaxLoader();
			},
			errorMsg: 'There was an error refreshing the shopping cart, please inform IT Web Experts about this error.'
		});
	},
	updateModuleMethods: function (action, noOrdertotalUpdate){
		var checkoutClass = this;
		var descText = (action == 'shipping' ? 'Shipping' : 'Payment');
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=update' + descText + 'Methods',
			type: 'post',
			beforeSendMsg: 'Updating ' + descText + ' Methods',
			success: function (data){
				$('#no' + descText + 'Address').hide();
				$('#' + action + 'Methods').html(data).show();
				if(action == 'payment')
				{
					if($('input[name="cot_gv"]', $(this)))
					{
						$('input[name="cot_gv"]', $(this)).each(function (){
							$(this).change(function (e){
								checkoutClass.setGV(($(':checkbox[name="cot_gv"]').is(':checked'))?'':'on');
							});
						});
					}
				}
				$('.' + action + 'Row').each(function (){
					checkoutClass.addRowMethods($(this));

					$('input[name="' + action + '"]', $(this)).each(function (){
						var setMethod = checkoutClass.setPaymentMethod;
						if (action == 'shipping'){
							setMethod = checkoutClass.setShippingMethod;
						}
						$(this).click(function (e, noOrdertotalUpdate){
							setMethod.call(checkoutClass, $(this));
								checkoutClass.updateOrderTotals();
						});
					});
				});
				checkoutClass.clickButton(descText.toLowerCase());
			},
			errorMsg: 'There was an error updating ' + action + ' methods, please inform IT Web Experts about this error.'
		});
	},
	updateShippingMethods: function (noOrdertotalUpdate){
		if (this.shippingEnabled == false){
			return false;
		}

		this.updateModuleMethods('shipping', noOrdertotalUpdate);
	},
	updatePaymentMethods: function (noOrdertotalUpdate){
		this.updateModuleMethods('payment', noOrdertotalUpdate);
	},
	setModuleMethod: function (type, method, successFunction){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=set' + (type == 'shipping' ? 'Shipping' : 'Payment') + 'Method&method=' + method,
			type: 'post',
			beforeSendMsg: 'Setting ' + (type == 'shipping' ? 'Shipping' : 'Payment') + ' Method',
			dataType: 'json',
			success: successFunction,
			errorMsg: 'There was an error setting ' + type + ' method, please inform IT Web Experts about this error.'
		});
	},
	setShippingMethod: function ($button){
		if (this.shippingEnabled == false){
			return false;
		}

		var checkoutClass = this;
		this.setModuleMethod('shipping', $button.val(), function (data){
		});
	},
	setPaymentMethod: function ($button){
		var checkoutClass = this;
		this.setModuleMethod('payment', $button.val(), function (data){
			$('.paymentFields').remove();
			if (data.inputFields != ''){
				$(data.inputFields).insertAfter($button.parent().parent());
			}
		});
	},
	loadAddressBook: function ($dialog, type){
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=getAddressBook&addressType=' + type,
			type: 'post',
			beforeSendMsg: 'Loading Address Book',
			success: function (data){
				$dialog.html(data);
			},
			errorMsg: 'There was an error loading your address book, please inform IT Web Experts about this error.'
		});
	},
	addCountryAjax: function ($input, fieldName, stateCol){
		var checkoutClass = this;
		$input.change(function (event, callBack){
			if ($(this).hasClass('required')){
				if ($(this).val() != '' && $(this).val() > 0){
					checkoutClass.addIcon($(this), 'success');
				}
			}
			var thisName = $(this).attr('name');
			var $origStateField = $('*[name="' + fieldName + '"]', $('#' + stateCol));
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: 'action=countrySelect&fieldName=' + fieldName + '&cID=' + $(this).val() + '&curValue=' + $origStateField.val(),
				type: 'post',
				beforeSendMsg: 'Getting Country\'s Zones',
				success: function (data){
					$('#' + stateCol).html(data);
					var $curField = $('*[name="' + fieldName + '"]', $('#' + stateCol));

					if ($curField.hasClass('required')){
						if (checkoutClass.fieldErrorCheck($curField, true, true) == false){
							checkoutClass.addIcon($curField, 'success');
						}else{
							checkoutClass.addIcon($curField, 'required');
						}
					}

					var processAddressFunction = checkoutClass.processBillingAddress;
					if (thisName == 'shipping_country'){
						processAddressFunction = checkoutClass.processShippingAddress;
					}
					
					var processFunction = function (){
						if ($(this).hasClass('required')){
							if (checkoutClass.fieldErrorCheck($(this)) == false){
								processAddressFunction.call(checkoutClass);
							}
						}else{
							processAddressFunction.call(checkoutClass);
						}
					};
					
					bindAutoFill($curField);
					
					if ($curField.attr('type') == 'select-one'){
						$curField.change(processFunction);
					}else{
						$curField.blur(processFunction);
					}

					if (callBack){
						callBack.call(checkoutClass);
					}
				},
				errorMsg: 'There was an error getting states, please inform IT Web Experts about this error.'
			});
		});
	},
	addCartRemoveMethod: function ($element){
		var checkoutClass = this;
		$element.click(function (){
			var $productRow = $(this).parent().parent();
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: $(this).attr('linkData'),
				type: 'post',
				beforeSendMsg: 'Removing Product From Cart',
				dataType: 'json',
				success: function (data){
					if (data.products == 0){
						document.location = checkoutClass.pageLinks.shoppingCart;
					}else{
						$productRow.remove();
						checkoutClass.updateFinalProductListing();
						checkoutClass.updateShippingMethods(true);
						checkoutClass.updateOrderTotals();
					}
				},
				errorMsg: 'There was an error updating shopping cart, please inform IT Web Experts about this error.'
			});
			return false;
		});
	},
	processBillingAddress: function (skipUpdateTotals){
		var hasError = false;
		var checkoutClass = this;
		$('select[name="billing_country"], input[name="billing_street_address"], input[name="billing_zipcode"], input[name="billing_city"], *[name="billing_state"]', $('#billingAddress')).each(function (){
			if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
				hasError = true;
			}
		});
		if (hasError == true){
			return;
		}

		this.setBillTo();
		
		if ($('#diffShipping:checked').size() <= 0 && this.loggedIn == true){
			this.setSendTo(false);
		}else{
			this.setSendTo(true);
		}
		if(skipUpdateTotals != true)
		{
			this.updateCartView();
			this.updateFinalProductListing();
			this.updatePaymentMethods(true);
			this.updateShippingMethods(true);
			this.updateOrderTotals();
		}
	},
	processShippingAddress: function (skipUpdateTotals){
		var hasError = false;
		var checkoutClass = this;
		$('select[name="delivery_country"], input[name="delivery_street_address"], input[name="delivery_zipcode"], input[name="delivery_city"]', $('#deliveryAddress')).each(function (){
			if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
				hasError = true;
			}
		});
		if (hasError == true){
			return;
		}

		this.setSendTo(true);
		if (this.shippingEnabled == true && skipUpdateTotals != true){
			this.updateShippingMethods(true);
		}
		if(skipUpdateTotals != true)
		{
			this.updateOrderTotals();
		}
	},
	setCheckoutAddress: function (type, useShipping){
		var selector = '#' + type + 'Address';
		var sendMsg = 'Setting ' + (type == 'shipping' ? 'Shipping' : 'Billing') + ' Address';
		var errMsg = type + ' address';
		if (type == 'shipping' && useShipping == false){
			selector = '#billingAddress';
			sendMsg = 'Setting Shipping Address';
			errMsg = 'billing address';
		}

		action = 'setBillTo';
		if (type == 'shipping'){
			action = 'setSendTo';
		}

		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			beforeSendMsg: sendMsg,
			dataType: 'json',
			data: 'action=' + action + '&' + $('*', $(selector)).serialize(),
			type: 'post',
			success: function (){
			},
			errorMsg: 'There was an error updating your ' + errMsg + ', please inform IT Web Experts about this error.'
		});
	},
	setBillTo: function (){
		this.setCheckoutAddress('billing', false);
	},
	setSendTo: function (useShipping){
		this.setCheckoutAddress('shipping', useShipping);
	},
	initCheckout: function (){
		var checkoutClass = this;

		if (this.loggedIn == false){
			$('#shippingAddress').hide();
			$('#shippingMethods').html('');
		}

		$('#checkoutNoScript').remove();
		$('#checkoutYesScript').show();

		$('.removeFromCart').each(function (){
			checkoutClass.addCartRemoveMethod($(this));
		});

		this.updateFinalProductListing();
		this.updateOrderTotals();

		$('#diffShipping').click(function (){
			if (this.checked){
				$('#shippingAddress').show();
				$('#shippingMethods').html('');
				$('#noShippingAddress').show();
				$('select[name="shipping_country"]').trigger('change');
			}else{
				$('#shippingAddress').hide();
				var errCheck = checkoutClass.processShippingAddress();
				if (errCheck == ''){
					$('#noShippingAddress').hide();
				}else{
					$('#noShippingAddress').show();
				}
			}
		});


		if (this.loggedIn == true){
			$('.shippingRow, .paymentRow').each(function (){
				checkoutClass.addRowMethods($(this));
			});

			$('input[name="payment"]').each(function (){
				$(this).click(function (){
					checkoutClass.setPaymentMethod($(this));
					checkoutClass.updateOrderTotals();
				});
			});

			if (this.shippingEnabled == true){
				$('input[name="shipping"]').each(function (){
					$(this).click(function (){
						checkoutClass.setShippingMethod($(this));
						checkoutClass.updateOrderTotals();
					});
				});
			}
		}

		if ($('#paymentMethods').is(':visible')){
			this.clickButton('payment');
		}

		if (this.shippingEnabled == true){
			if ($('#shippingMethods').is(':visible')){
				this.clickButton('shipping');
			}
		}

		$('input, password', $('#billingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio'){
				if ($(this).attr('type') == 'password'){
					$(this).blur(function (){
						if ($(this).hasClass('required')){
							checkoutClass.fieldErrorCheck($(this));
						}
					});
					/* Used to combat firefox 3 and it's auto-populate junk */
					$(this).val('');

					if ($(this).attr('name') == 'password'){
						$(this).focus(function (){
							$(':password[name="confirmation"]').val('');
						});

						var rObj = getFieldErrorCheck($(this));
						$(this).pstrength({
							addTo: '#pstrength_password',
							minchar: rObj.minLength
						});
					}
				}else{
					$(this).blur(function (){
						if ($(this).hasClass('required')){
							checkoutClass.fieldErrorCheck($(this));
						}
					});
					bindAutoFill($(this));
				}

				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this), true, true) == false){
						checkoutClass.addIcon($(this), 'success');
					}else{
						checkoutClass.addIcon($(this), 'required');
					}
				}
			}
		});
		/*
		$('select[name="billing_country"], input[name="billing_street_address"], input[name="billing_zipcode"], input[name="billing_city"]', $('#billingAddress')).each(function (){
			var processFunction = function (){
				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this)) == false){
						checkoutClass.processBillingAddress();
					}
				}else{
					checkoutClass.processBillingAddress();
				}
			};
			
			$(this).unbind('blur');
			if ($(this).attr('type') == 'select-one'){
				$(this).change(processFunction);
			}else{
				$(this).blur(processFunction);
			}
			bindAutoFill($(this));
		});
		*/
		$('input,select[name="billing_country"], ', $('#billingAddress')).each(function (){
			var processFunction = function (){
				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this)) == false){
						if($(this).attr('name')=='billing_country' || $(this).attr('name')=='billing_zipcode' || $(this).attr('name')=='billing_state')
						{
							checkoutClass.processBillingAddress();
						}else
						{
							checkoutClass.processBillingAddress(true);
						}
					}
				}else{
					if($(this).attr('name')=='billing_country' || $(this).attr('name')=='billing_zipcode' || $(this).attr('name')=='billing_state')
					{
						checkoutClass.processBillingAddress();
					}else
					{
						checkoutClass.processBillingAddress(true);
					}
				}
			};
			
			$(this).unbind('blur');
			if ($(this).attr('type') == 'select-one'){
				$(this).change(processFunction);
			}else{
				$(this).blur(processFunction);
			}
			bindAutoFill($(this));
		});

		$('input[name="billing_email_address"]').each(function (){
			$(this).unbind('blur').blur(function (){
				var $thisField = $(this);
			
				if (checkoutClass.initializing == true){
					checkoutClass.addIcon($thisField, 'required');
				}else{
					if (this.changed == false) return;
					if (checkoutClass.fieldErrorCheck($thisField, true, true) == false){
						this.changed = false;
						checkoutClass.queueAjaxRequest({
							url: checkoutClass.pageLinks.checkout,
							data: 'action=checkEmailAddress&emailAddress=' + $thisField.val(),
							type: 'post',
							beforeSendMsg: 'Checking Email Address',
							dataType: 'json',
							success: function (data){
								$('.success, .error', $thisField.parent()).hide();
								if (data.success == false){
									checkoutClass.addIcon($thisField, 'error', data.errMsg.replace('/n', "\n"));
									alert(data.errMsg.replace('/n', "\n").replace('/n', "\n").replace('/n', "\n"));
								}else{
									checkoutClass.addIcon($thisField, 'success');
								}
							},
							errorMsg: 'There was an error checking email address, please inform IT Web Experts about this error.'
						});
					}
				}
			}).keyup(function (){
				this.changed = true;
			});
			bindAutoFill($(this));
		});
		/*
		$('input', $('#shippingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							checkoutClass.processShippingAddress();
						}else{
							$('#noShippingAddress').show();
							$('#shippingMethods').hide();
						}
					}else{
						checkoutClass.processShippingAddress();
					}
				};
			
				$(this).blur(processAddressFunction);
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					var icon = 'required';
					if ($(this).val() != '' && checkoutClass.fieldErrorCheck($(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon($(this), icon);
				}
			}
		});
		*/
		$('input,select[name="shipping_country"]', $('#shippingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							if($(this).attr('name')=='shipping_country' || $(this).attr('name')=='shipping_zipcode' || $(this).attr('name')=='delivery_state')
							{
								checkoutClass.processShippingAddress();
							}else
							{
								checkoutClass.processShippingAddress(true);
							}
						}else{
							$('#noShippingAddress').show();
							$('#shippingMethods').hide();
						}
					}else{
						if($(this).attr('name')=='shipping_country' || $(this).attr('name')=='shipping_zipcode' || $(this).attr('name')=='delivery_state')
						{
							checkoutClass.processShippingAddress();
						}else
						{
							checkoutClass.processShippingAddress(true);
						}
					}
				};
			
				$(this).blur(processAddressFunction);
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					var icon = 'required';
					if ($(this).val() != '' && checkoutClass.fieldErrorCheck($(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon($(this), icon);
				}
			}
		});

		$('select[name="shipping_country"], select[name="billing_country"]').each(function (){
			var $thisName = $(this).attr('name');
			var fieldType = 'billing';
			if ($thisName == 'shipping_country'){
				fieldType = 'shipping';
			}
			checkoutClass.addCountryAjax($(this), fieldType + '_state', 'stateCol_' + fieldType);
		});
		
		$('*[name="billing_state"], *[name="delivery_state"]').each(function (){
			var processAddressFunction = checkoutClass.processBillingAddress;
			if ($(this).attr('name') == 'delivery_state'){
				processAddressFunction = checkoutClass.processShippingAddress;
			}
			
			var processFunction = function (){
				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this)) == false){
						processAddressFunction.call(checkoutClass);
					}
				}else{
					processAddressFunction.call(checkoutClass);
				}
			}
		
			if ($(this).attr('type') == 'select-one'){
				$(this).change(processFunction);
			}else{
				$(this).blur(processFunction);
			}
			bindAutoFill($(this));
		});

		$('#updateCartButton').click(function (){
			checkoutClass.showAjaxLoader();
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: 'action=updateQuantities&' + $('input', $('#shoppingCart')).serialize(),
				type: 'post',
				beforeSendMsg: 'Updating Product Quantities',
				dataType: 'json',
				success: function (){
					checkoutClass.updateCartView();
					checkoutClass.updateFinalProductListing();
					if ($('#noPaymentAddress:hidden').size() > 0){
						checkoutClass.updatePaymentMethods();
						checkoutClass.updateShippingMethods(true);
					}
					checkoutClass.updateOrderTotals();
				},
				errorMsg: 'There was an error updating shopping cart, please inform IT Web Experts about this error.'
			});
			return false;
		});

		function checkAllErrors(){
			var errMsg = '';
			if ($('.required_icon:visible', $('#billingAddress')).size() > 0){
				errMsg += 'Please fill in all required fields in "Billing Address"' + "\n";
			}

			if ($('.error_icon:visible', $('#billingAddress')).size() > 0){
				errMsg += 'Please correct fields with errors in "Billing Address"' + "\n";
			}

			if ($('#diffShipping:checked').size() > 0){
				if ($('.required_icon:visible', $('#shippingAddress')).size() > 0){
					errMsg += 'Please fill in all required fields in "Shipping Address"' + "\n";
				}

				if ($('.error_icon:visible', $('#shippingAddress')).size() > 0){
					errMsg += 'Please correct fields with errors in "Shipping Address"' + "\n";
				}
			}

			if (errMsg != ''){
				errMsg = '------------------------------------------------' + "\n" +
				'                 Address Errors                 ' + "\n" +
				'------------------------------------------------' + "\n" +
				errMsg;
			}

			if ($(':radio[name="payment"]:checked').size() <= 0){
				if ($('input[name="payment"]:hidden').size() <= 0){
					errMsg += '------------------------------------------------' + "\n" +
					'           Payment Selection Error              ' + "\n" +
					'------------------------------------------------' + "\n" +
					'You must select a payment method.' + "\n";
				}
			}

			if (checkoutClass.shippingEnabled === true){
				if ($(':radio[name="shipping"]:checked').size() <= 0){
					if ($('input[name="shipping"]:hidden').size() <= 0){
						errMsg += '------------------------------------------------' + "\n" +
						'           Shipping Selection Error             ' + "\n" +
						'------------------------------------------------' + "\n" +
						'You must select a shipping method.' + "\n";
					}
				}
			}
			if(this.ccgvInstalled == true)
			{
				if($('input[name="gv_redeem_code"]').val() == 'redeem code')
				{
					$('input[name="gv_redeem_code"]').val('');
				}
			}

			if(this.kgtInstalled == true)
			{
				if($('input[name="coupon"]').val() == 'redeem code')
				{
					$('input[name="coupon"]').val('');
				}
			}

			if (errMsg.length > 0){
				alert(errMsg);
				return false;
			}else{
				return true;
			}
		}

		$('#checkoutButton').click(function() {
			return checkAllErrors();
		});

		if (this.ccgvInstalled == true){
			$('input[name="gv_redeem_code"]').focus(function (){
				if ($(this).val() == 'redeem code'){
					$(this).val('');
				}
			});

			$('#voucherRedeem').click(function (){
				checkoutClass.queueAjaxRequest({
					url: checkoutClass.pageLinks.checkout,
					data: 'action=redeemVoucher&code=' + $('input[name="gv_redeem_code"]').val(),
					type: 'post',
					beforeSendMsg: 'Validating Coupon',
					dataType: 'json',
					success: function (data){
						if (data.success == false){
							alert('Coupon is either invalid or expired.');
						}
						checkoutClass.updateOrderTotals();
					},
					errorMsg: 'There was an error redeeming coupon, please inform IT Web Experts about this error.'
				});
				return false;
			});
			if($('input[name="cot_gv"]', $('#paymentMethods')))
			{
				$('input[name="cot_gv"]', $('#paymentMethods')).each(function (){
					$(this).change(function (e){
						checkoutClass.setGV(($(':checkbox[name="cot_gv"]').is(':checked'))?'':'on');
					});
				});
			}
		}
		if (this.kgtInstalled == true){
			$('input[name="coupon"]').focus(function (){
				if ($(this).val() == 'coupon code'){
					$(this).val('');
				}
			});
			$('#voucherRedeemCoupon').click(function (){
				checkoutClass.queueAjaxRequest({
					url: checkoutClass.pageLinks.checkout,
					data: 'action=redeemVoucher&code=' + $('input[name="coupon"]').val(),
					type: 'post',
					beforeSendMsg: 'Validating Coupon',
					dataType: 'json',
					success: function (data){
						if (data.success == false){
							alert('Coupon is either invalid or expired.');
							
						}
						//alert(data.message);
						checkoutClass.updateOrderTotals(true);
					},
					errorMsg: 'There was an error redeeming coupon, please inform IT Web Experts about this error.'
				});
				return false;
			});
		}
		if (this.loggedIn == true && this.showAddressInFields == true){
			$('*[name="billing_state"]').trigger('change');
			$('*[name="delivery_state"]').trigger('change');
		}

		this.initializing = false;
	}
}