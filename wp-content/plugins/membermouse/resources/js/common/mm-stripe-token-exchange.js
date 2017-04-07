var MM_StripeTokenExchanger = Class.extend({
	
	doTokenExchange: function()
	{
		try
		{ 
			/*
			 * Check to see that a coupon with free type is not used.
			 * If it is, no need to send information (or initiate tokenization process).
			 */
			if(mmjs.hasFreeCoupon())
			{ 
				mmjs.usingTokenExchange = false;
				mmjs.submitCheckoutForm(false);
				return true;
			}
			
			var tokenParameters = { number: jQuery('#mm_field_cc_number').val(),
					  				cvc: jQuery('#mm_field_cc_cvv').val(),
					  				exp_month: jQuery('#mm_field_cc_exp_month').val(),
					  				exp_year: jQuery('#mm_field_cc_exp_year').val(),
					  				name: jQuery('#mm_field_first_name').val() + ' ' + jQuery('#mm_field_last_name').val()};
			
			//Add the address fields that are present in the form
			var optionalAddressFieldMapping = {"mm_field_billing_address":"address_line1",
											   "mm_field_billing_city":"address_city",
											   "mm_field_billing_state":"address_state",
											   "mm_field_billing_zip":"address_zip",
											   "mm_field_billing_country":"address_country"
											  };
			for (var optionalFormField in optionalAddressFieldMapping)
			{
				if (jQuery("#" + optionalFormField).length)
				{
					var tmpIndex = optionalAddressFieldMapping[optionalFormField];
					tokenParameters[tmpIndex] = jQuery("#" + optionalFormField).val();
				}
			}
			
			Stripe.setPublishableKey(stripeJSInfo.stripePublishableKey);
			Stripe.card.createToken(tokenParameters, mmStripeTokenExchanger.stripeResponseHandler);
		}
		catch (e)
		{
			mmStripeTokenExchanger.errorHandler(e.message);
		}
		return false; //prevents the form submission, we will do that ourselves when the token exchange is completed
	},
	
	errorHandler: function(errorMessage)
	{
		//for now, alert the message
		alert(errorMessage);
	},
	
	stripeResponseHandler: function(status, response) 
	{
		  if (response.error) 
		  {
			  // Show the errors on the form
			  var errorMessage = (response.error.message)?response.error.message:"There was an error processing your payment information";
			  mmStripeTokenExchanger.errorHandler(errorMessage);
			  return false;
		  } 
		  else 
		  {
			  // response contains id and card, which contains additional card details
			  mmjs.usingTokenExchange = true;
			  mmjs.addPaymentTokenToForm(response.id);
			  mmjs.submitCheckoutForm(false);
			  return true;
		  }
	}
});
var mmStripeTokenExchanger = new MM_StripeTokenExchanger();
mmjs.addPrecheckoutCallback('onsite',mmStripeTokenExchanger.doTokenExchange);