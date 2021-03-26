(function($){
	$(document).ready(function () {

        // Inserts vat verification button under vat input field
        jQuery('.append-button').each(function(){
            var item = jQuery(this);
            var description = item.attr('vat-button');
            item.parent().append('<button type="button" class="button default" id="vatButton">'+description+'</button>');
        });

        // Change button to default color if user edits vat
        $("#billing_vat").on("input", function(){
            document.getElementById("vatButton").className="button default";
        });

        /*
        * OnClick event for vat verification button
        * Gets company information from cvrapi.dk based on user vat input and country
        * Changes vatButton style based on GET success
        */
        $("#vatButton").click(function(){
            var vatButton = document.getElementById("vatButton");

            var vat = $('#billing_vat').val();
            var country =$('#billing_country').val();

            var buttonFailure = "CVR nr. er ugyldigt, prøv venligst igen";
            var buttonSuccess = "Oplysninger er successfuldt hentet";
            
            $.getJSON('//cvrapi.dk/api?search=' + vat + "&country=" + country, function(data) {
                if  (vat == "") {
                    vatButton.innerHTML=buttonFailure;
                    vatButton.className="button failure";
                }
                else if (vat != data.vat){
                    vatButton.innerHTML=buttonFailure;
                    vatButton.className="button failure";
                }
                else{
                    vatButton.innerHTML=buttonSuccess;
                    vatButton.className="button success";

                    $('#billing_address_1').val(data.address);
                    $('#billing_company').val(data.name);
                    $('#billing_city').val(data.city);
                    $('#billing_postcode').val(data.zipcode);
                    $('#billing_phone').val(data.phone);
                }
            });
        });
	});
})(jQuery);