jQuery(document).ready(function ($) {
    $(".digits_only").keydown(function (event) {
        if( !(event.keyCode == 8                                // backspace
            || event.keyCode == 9                               // tab
            || event.keyCode == 46                              // delete
            || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end
            || (event.keyCode >= 48 && event.keyCode <= 57)     // numbers on keyboard
            || (event.keyCode >= 96 && event.keyCode <= 105))   // number on keypad
        ) {
            event.preventDefault();     // Prevent character input
            alert("Only numbers are allowed.");
        }
    });
});