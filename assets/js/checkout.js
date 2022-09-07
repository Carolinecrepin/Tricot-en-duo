// This is your test publishable API key.
var stripe = Stripe("pk_test_51LPktXCSZL0O8yPqrFjiH2kslbkSqIq8U6pXe6t8OySByeVmKe1dUD0exMEf0vQaIuAtfCCyu6GudLFKRrs5bGVs00WmjkvDhc");

var checkoutButton = document.getElementById("checkout-button");

checkoutButton.addEventListener("click", function() {
    fetch('/create-checkout-session', {
        method: "POST",
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (session) {
            return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .then(function (result) {
            //If redirectToCheckout fails due to a browser or network
            //error, you should display the localized error message to your
            //customer using error.message.
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(function (error) {
            console.error("Error:", error);
        });
});