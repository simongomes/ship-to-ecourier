//! phpcs:ignoreFile
//! admin.js - for Ship To Ecourier WordPress Plugin.
//! version 	: 1.0.1
//! author 		: Simon Gomes
//! author uri	: https://simongomes.dev
//! license 	: GPLv3
//! license uri : https://www.gnu.org/licenses/gpl-3.0.html

;(function ($) {
    let bookingForm = $("#ste-metabox-wrap");
    $("#submit_ste_ecourier_parcel").on("click", function (e) {
        e.preventDefault();
        let _isValid = true;
        let parcelData = {
            recipient_name: $( "#recipient_name", bookingForm ).val(),
            recipient_mobile: $( "#recipient_mobile", bookingForm ).val(),
            recipient_city: $( "#recipient_city", bookingForm ).val(),
            recipient_area: $( "#recipient_area", bookingForm ).val(),
            recipient_thana: $( "#recipient_thana", bookingForm ).val(),
            recipient_zip: $( "#recipient_zip", bookingForm ).val(),
            recipient_address: $( "#recipient_address", bookingForm ).val(),
            payment_method: $( "#payment_method", bookingForm ).val(),
            package_code: $( "#package_code", bookingForm ).val(),
            product_id: $( "#product_id", bookingForm ).val(),
            product_price: $( "#product_price", bookingForm ).val(),
            number_of_item: $( "#number_of_item", bookingForm ).val(),
            comments: $( "#comments", bookingForm ).val(),
            submit_ste_ecourier_parcel: $( "#submit_ste_ecourier_parcel", bookingForm ).val(),
            action: 'ste_booking_metabox_form',
            _nonce: STE_ADMIN.nonce,
        };

        $.each( parcelData, function (index, value) {
            if ( '' === value.trim() ) {
                _isValid = false;
            }
        });
        if ( ! _isValid ) {
            $( '.error-message' ).text( STE_ADMIN.error.required );
        } else {
            $( '.error-message' ).text( '' );
            $.post(STE_ADMIN.ajaxurl, parcelData, function ( response ) {
                if ( ! response.success ) {
                    $( '.error-message' ).text( response.data.message );
                } else {
                    $( '.error-message' ).text( '' );
                }
            })
        }

    });
})(jQuery);