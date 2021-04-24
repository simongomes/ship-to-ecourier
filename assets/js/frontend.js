//! phpcs:ignoreFile
//! frontend.js - for Ship To Ecourier WordPress Plugin.
//! version 	: 1.0.1
//! author 		: Simon Gomes
//! author uri	: https://simongomes.dev
//! license 	: GPLv3
//! license uri : https://www.gnu.org/licenses/gpl-3.0.html

(function ($) {
	let errorMessage = "";
	let errorContainer = $("#error-container");
	let trackingInput = $("#track-form .tracking-code");
	let trackNotFound = $("#track-not-found");
	let trackResult = $("#package-information");
	let trackInfo = $(".track-shipment-info > ul", trackResult);

	$("#track-form").on("submit", function (e) {
		e.preventDefault();
		trackNotFound.hide();
		trackResult.hide();
		trackInfo.html("");

		// Check if tracking code empty then return.
		if (undefined === trackingInput || "" === trackingInput.val().trim()) {
			errorMessage = "Please provide a tracking code.";
			$(".error-message", errorContainer).text(errorMessage);
			errorContainer.fadeIn();
			return;
		}

		if (
			(trackingInput.val().startsWith("ECR") ||
				trackingInput.val().startsWith("BL")) &&
			11 <= trackingInput.val().length
		) {
			errorMessage = "";
			errorContainer.fadeOut();
		} else {
			errorMessage =
				"Tracking number starts with ECR or BL and minimum 11 characters";
			$(".error-message", errorContainer).text(errorMessage);
			errorContainer.fadeIn();
			return;
		}

		let data = $(this).serialize();
		$.post(STE.ajaxurl, data, function (response) {1``
			if (response.success) {
				const result = JSON.parse(response.data.message);

				if (!result.success) {
					trackNotFound.fadeIn();
				} else {
					const parcel = result.query_data;
					// Set Tracking Number
					$(".tracking-number", trackResult).text(parcel.REFID);

					// Set Order Date
					$(".order-date", trackResult).text(
						moment(parcel.r_time).format("MMMM D YYYY")
					);

					// Set Company Name
					$(".company-name", trackResult).text(parcel.company);

					// Set Customer Info
					$(".customer-name", trackResult).text(parcel.r_name);
					$(".customer-address", trackResult).text(
						parcel.r_address + ", " + parcel.r_area
					);

					// Calculate Elapsed Time
					const orderDate = moment(
						parcel.r_time,
						"YYYY-MM-DD HH:mm:ss"
					);
					const elapseDate =
						"Delivered" === parcel.status[0].status
							? moment(
									parcel.status[0].time,
									"YYYY-MM-DD HH:mm:ss"
							  ).subtract(1, "days")
							: moment(
									new Date(),
									"YYYY-MM-DD HH:mm:ss"
							  ).subtract(1, "days");

					const elaplseDays = moment
						.utc(elapseDate.diff(orderDate))
						.format("D");
					const elaplseHours = moment
						.utc(elapseDate.diff(orderDate))
						.format("H");
					const elaplseMinutes = moment
						.utc(elapseDate.diff(orderDate))
						.format("m");

					const elapseTime =
						("0" !== elaplseDays ? elaplseDays + " Days, " : "") +
						("0" !== elaplseHours
							? elaplseHours + " Hours, "
							: "") +
						("0" !== elaplseMinutes
							? elaplseMinutes + " Minutes"
							: "");

					// Set Elapsed Time
					$(".elapse-time", trackResult).text(elapseTime);

					// Shipment Statuses
					const { status } = parcel;
					const shipment_status = _.groupBy(status, shipmentDate);

					for (const index in shipment_status) {
						let shipment_items_html = "";
						shipment_status[index].forEach((item) => {
							shipment_items_html +=
								"<li><h4>" +
								moment(item.time).format("h:mm") +
								"</h4><div><b>" +
								item.status +
								"</b> <strong>Comment:</strong> " +
								(null !== item.comment ? item.comment : "") +
								"</div></li>";
						});
						const html =
							"<li class='active'>" +
							moment(index).format("dddd, MMMM DD YYYY") +
							"<ul>" +
							shipment_items_html +
							"</ul></li>";
						trackInfo.append(html);
					}
					// Make the result DOM visible
					trackResult.fadeIn();
				}
			} else {
				errorMessage = result;
				$(".error-message", errorContainer).text(errorMessage);
				errorContainer.fadeIn();
			}
		});
	});
	const shipmentDate = (item) => moment(item.time).format("YYYY-MM-DD");
})(jQuery);
