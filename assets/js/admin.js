//! phpcs:ignoreFile
//! admin.js - for Ship To Ecourier WordPress Plugin.
//! version 	: 1.0.1
//! author 		: Simon Gomes
//! author uri	: https://simongomes.dev
//! license 	: GPLv3
//! license uri : https://www.gnu.org/licenses/gpl-3.0.html

;(function ($) {
    // eCourier Parcel Booking - start
    let parcelSubmitButton = $("#submit-ste-ecourier-parcel");
    let bookingFormWrap = $( "#ste-metabox-wrap" );
    let errorMessage = $( '.error-message' );
    let bookingForm = $( '#ste-booking-metabox-form' );
    let bookingMetaBoxMessage = $( '#ste-booking-metabox-message' );

    parcelSubmitButton.on("click", function (e) {
        e.preventDefault();
        parcelSubmitButton.prop( 'disabled', true );
        let _isValid = true;
        let parcelData = {
            recipient_name: $( "#recipient_name", bookingFormWrap ).val(),
            recipient_mobile: $( "#recipient_mobile", bookingFormWrap ).val().replace( "+88", "" ),
            recipient_city: $( "#recipient_city", bookingFormWrap ).val(),
            recipient_area: $( "#recipient_area", bookingFormWrap ).val(),
            recipient_thana: $( "#recipient_thana", bookingFormWrap ).val(),
            recipient_zip: $( "#recipient_zip", bookingFormWrap ).val(),
            recipient_address: $( "#recipient_address", bookingFormWrap ).val(),
            payment_method: $( "#payment_method", bookingFormWrap ).val(),
            package_code: $( "#package_code", bookingFormWrap ).val(),
            product_id: $( "#product_id", bookingFormWrap ).val(),
            product_price: $( "#product_price", bookingFormWrap ).val(),
            number_of_item: $( "#number_of_item", bookingFormWrap ).val(),
            comments: $( "#comments", bookingFormWrap ).val(),
            submit_ste_ecourier_parcel: $( "#submit_ste_ecourier_parcel", bookingFormWrap ).val(),
            action: 'ste_booking_metabox_form',
            _nonce: STE_ADMIN.nonce,
        };

        $.each( parcelData, function (index, value) {
            if ( '' === value.trim() ) {
                _isValid = false;
            }
        });
        if ( ! _isValid ) {
            errorMessage.text( STE_ADMIN.error.required );
            parcelSubmitButton.prop( 'disabled', false );
        } else {
            errorMessage.text( '' );
            $.post(STE_ADMIN.ajaxurl, parcelData, function ( response ) {
                if ( ! response.success ) {
                    errorMessage.text( response.data.message );
                    parcelSubmitButton.prop( 'disabled', false );
                } else {
                    errorMessage.text( '' );
                    const ecourier_esponse = JSON.parse( response.data.message );
                    if ( ! ecourier_esponse.success  ) {
                        errorMessage.text( ecourier_esponse.errors[0] );
                        parcelSubmitButton.prop( 'disabled', false );
                    } else {
                        errorMessage.text( '' );
                        $( ".title", bookingMetaBoxMessage ).text( ecourier_esponse.message );
                        $( ".tracking_id", bookingMetaBoxMessage ).text( ecourier_esponse.ID );
                        bookingForm.hide();
                        bookingMetaBoxMessage.show();
                        window.location.reload();
                    }
                }
            })
        }
    });
    // eCourier Parcel Booking - end

    // eCourier Print Label - start
    let labelPrintButton = $("#ste-print-label");

    labelPrintButton.on( 'click', function (e) {
        e.preventDefault();
        labelPrintButton.prop( 'disabled', true );

        let labelData = {
            tracking : labelPrintButton.val(),
            action   : 'ste_label_print',
            _nonce   : STE_ADMIN.nonce,
        }

        $.post(STE_ADMIN.ajaxurl, labelData, function ( response ) {
            if ( ! response.success ) {
                errorMessage.text(response.data.message);
            } else {
                errorMessage.text("");

                // On success open the label in a new window.
                open(response.data.message);
            }
            labelPrintButton.prop( 'disabled', false );
        });
    })
    // eCourier Print Label - end

})(jQuery, window);