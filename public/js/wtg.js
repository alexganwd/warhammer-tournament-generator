// <!-- Menu Toggle Script -->

$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});



function validateSignUpForm() {
    var password = $( "#passwordSignUp" ).val();
    var passwordCheck = $( "#passwordSignUpVerification").val();
    var email = $( "#signupEmail").val();
    var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;


    // Check length
    if (password.length < 3){
        $( "#formDataValidation").html("Password length is smaller than 8!").css('color', 'red');
        return false;
    }

    else if (email== '' || !re.test(email))
    {
        $( "#formDataValidation").html("You have to provide a valid e-mail!").css('color', 'red');
        return false;
    }

    // Check Password MissMatch

    else if (password != passwordCheck) {
        $( "#formDataValidation").html("Password does not match!").css('color', 'red');
        return false;
    }
    else {
        $.ajax({
            type: "POST",
            url: 'signup',
            data: $("#signUpForm").serialize(),
            success: function (data) {
                if (data == "OK") {
                    $("#formDataValidation").html("Registration successfully. You can close this window").css('color', 'green');
                    // Let's block the fields to avoid resubmitting requests.
                    $('#signupInput').attr('readonly', true);
                }

                else
                    $( "#formDataValidation").html("Registration Failed. Please try again later").css('color', 'red');
            }
        });
        return true;

        }
}

function validateAndLogin() {
    var password = $("#passwordLogin").val();
    var email = $("#loginEmail").val();
    var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

    if (email == '' || !re.test(email)) {

        $("#loginDataValidation").html("You have to provide a valid e-mail!").css('color', 'red');

    }
    else {
        $.ajax({
            type: "POST",
            url: 'login',
            data: $("#loginForm").serialize(),
            success: function (data) {
                if (data != "KO") {
                    $("#loginDataValidation").html("Login successfully. You can close this window").css('color', 'green');
                    // Let's block the fields to avoid resubmitting requests.
                    $('input.loginInput').attr('readonly', true);
                    $('#loginForm').trigger("reset");
                    $('#LoginModal').modal('toggle');
                    location.reload();
                }
                else {
                    $("#loginDataValidation").html("Login failed. Please try again later").css('color', 'red');
                }
            }});
    }
}

function logOut() {
    $.ajax({
        type: "GET",
        url: 'logout',
        success: function (data) {
            alert(data);
            if (data == "OK") {
                alert("LogOutreload");//location.reload();
            }
        }});
};

// $( "#btnLogOut" ).clickEvent(logOut());
$( "#btnsubmit" ).click(validateSignUpForm);
$( "#btnLogin" ).click(validateAndLogin);
$( "#btnLogOut" ).click(
    function () {
        $.ajax({
            type: "GET",
            url: 'logout',
            success: function (data) {
                if (data == "OK") {
                    location.reload();
                }
            }});
    });