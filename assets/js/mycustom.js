// Allow only letters and single spaces for name
$(document).on('input', '.onlytextnonumber', function () {  console.log('textss')
    let value = $(this).val();

    // Remove everything that's not a letter or space
    value = value.replace(/[^A-Za-z ]/g, '');

    // Replace multiple spaces with a single space
    value = value.replace(/\s{2,}/g, ' ');

    // Remove leading space
    value = value.replace(/^\s+/g, '');

    $(this).val(value);
});

// Allow only digits for mobile number
$(document).on('input', '.onlynumbersnoname', function () {
    let value = $(this).val();

    // Remove non-digit characters
    value = value.replace(/\D/g, '');

    $(this).val(value);
});

$(".no-special-chars").on("input", function () {
    var value = $(this).val();
    var filteredValue = value.replace(/[^a-zA-Z0-9\s]/g, ""); // Allow only letters, numbers, and spaces

    if (value !== filteredValue) {
        $(this).val(filteredValue); // Set corrected value
        $(this).siblings(".error-message").show(); // Show error message
    } else {
        $(this).siblings(".error-message").hide(); // Hide error message if input is valid
    }
    });