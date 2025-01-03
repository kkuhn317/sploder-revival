// Store username in local storage from HTML

function storeUsername() {
    var username = document.getElementById('login_username').value;
    localStorage.setItem('login_username', username);
}

// Set username upon re-login
function setUsername() {
    // Try catch
    try {
        var username = localStorage.getItem('login_username');
        document.getElementById('login_username').value = username;
        // Change control to password
        document.getElementById('login_password').focus();
    }  catch (err) {}
}