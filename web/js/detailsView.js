$(document).ready(function () {
    // Get the modal
    let popups = document.getElementsByClassName('modal');
    //register the popups
    let modals = [{modal: popups[1], btn: document.getElementById("imageBtn")}, {
        modal: popups[0],
        btn: document.getElementById("promoteProductBtn")
    }];
    for (let modal of modals) {
        // Get the button that opens the modal

// Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
        modal.btn.onclick = function () {
            window.onclick = function (event) {
                // When the user clicks anywhere outside of the modal, close it
                if (event.target === modal.modal) {
                    $(modal.modal).fadeOut();
                }
            };
            modal.modal.style.display = "block";
        };

// When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.modal.style.display = "none";
        }
    }
});