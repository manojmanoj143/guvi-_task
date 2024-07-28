$(document).ready(function() {
    $('#registerForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: 'php/register.php', // Updated URL path
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#message').html(response);
                if (response.includes('Registration successful!')) {
                    // Optionally, redirect to login page
                    window.location.href = 'login.html';
                }
            },
            error: function(xhr, status, error) {
                $('#message').html('Error: ' + error);
            }
        });
    });
});
