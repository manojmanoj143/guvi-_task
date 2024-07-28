$(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    localStorage.setItem('session_id', response.session_id);
                    window.location.href = 'profile.html';
                } else {
                    $('#message').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#message').html('Error: ' + error);
            }
        });
    });
});
