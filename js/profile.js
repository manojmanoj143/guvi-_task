$(document).ready(function() {
    const sessionId = localStorage.getItem('session_id');

    if (!sessionId) {
        window.location.href = 'login.html'; // Redirect to login if not authenticated
        return;
    }

    // Fetch profile data
    $.ajax({
        url: 'php/profile.php',
        type: 'GET',
        data: { session_id: sessionId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#dob').val(response.data.dob);
                $('#fathername').val(response.data.fathername);
                $('#mothername').val(response.data.mothername);
                $('#contact').val(response.data.contact);
            } else {
                $('#message').html(response.message);
            }
        },
        error: function(xhr, status, error) {
            $('#message').html('Error: ' + error);
        }
    });

    // Update profile
    $('#profileForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: 'php/update_profile.php',
            type: 'POST',
            data: $(this).serialize() + '&session_id=' + sessionId,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#message').html('Profile updated successfully!');
                    setTimeout(function() {
                        window.location.href = 'dashboard.html'; // Redirect to dashboard after 2 seconds
                    }, 2000); // Delay of 2 seconds
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
