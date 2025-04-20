<script>
    let logoutTimer;

    function autoLogout() {
        console.log('AutoLogout triggered'); // Debugging log
        fetch('/auto-logout', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.redirected) {
                alert('You have been logged out due to inactivity.'); // Show alert before redirecting
                window.location.href = response.url; // Redirect to the login page
            }
        }).catch(error => console.error('Error during autoLogout:', error));
    }

    function resetLogoutTimer() {
        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(autoLogout, 60000); // 1 minute = 60000 milliseconds
    }

    // Reset the logout timer on user activity
    ['mousemove', 'keydown', 'click', 'scroll'].forEach(event => {
        window.addEventListener(event, resetLogoutTimer);
    });

    resetLogoutTimer();

    // Periodically check session status to handle server-side logout
    setInterval(() => {
        fetch("{{ route('check-session-status') }}", {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (response.status === 401) {
                alert('You have been logged out due to inactivity.');
                window.location.href = "{{ route('login') }}";
            }
        }).catch(error => console.error('Error checking session status:', error));
    }, 60000); // Check every 60 seconds


</script>