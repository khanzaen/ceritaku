function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function switchModal(fromModalId, toModalId) {
    closeModal(fromModalId);
    setTimeout(() => openModal(toModalId), 150);
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling.querySelector('.material-symbols-outlined');
    
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        button.textContent = 'visibility';
    }
}

// Toast notification system
function showToast(message, type = 'info', duration = 3000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    const toastId = 'toast-' + Date.now();
    toast.id = toastId;
    
    // Toast colors based on type
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: 'check_circle',
        error: 'error',
        warning: 'warning',
        info: 'info'
    };
    
    toast.className = `${colors[type] || colors.info} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[320px] pointer-events-auto transform transition-all duration-300 ease-out opacity-0 translate-x-full`;
    
    toast.innerHTML = `
        <span class="material-symbols-outlined text-[24px]">${icons[type] || icons.info}</span>
        <span class="flex-1 font-medium">${message}</span>
        <button onclick="closeToast('${toastId}')" class="material-symbols-outlined text-[20px] hover:bg-white/20 rounded p-1 transition-colors">close</button>
    `;
    
    container.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-full');
    }, 10);
    
    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            closeToast(toastId);
        }, duration);
    }
}

function closeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (!toast) return;
    
    toast.classList.add('opacity-0', 'translate-x-full');
    
    setTimeout(() => {
        toast.remove();
    }, 300);
}

// Logout confirmation modal functions
function showLogoutConfirm() {
    const modal = document.getElementById('logoutConfirmModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeLogoutConfirm() {
    const modal = document.getElementById('logoutConfirmModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function confirmLogout() {
    closeLogoutConfirm();
    
    // Show loading toast
    showToast('Logging out...', 'info', 0);
    
    fetch('/auth/logout', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Close all toasts
        const container = document.getElementById('toastContainer');
        if (container) container.innerHTML = '';
        
        // Show success toast
        showToast('Logout successful! Redirecting...', 'success', 1500);
        
        // Redirect after short delay
        setTimeout(() => {
            window.location.href = '/';
        }, 1500);
    })
    .catch(error => {
        console.error('Logout error:', error);
        showToast('Logout failed. Redirecting anyway...', 'warning', 1500);
        setTimeout(() => {
            window.location.href = '/';
        }, 1500);
    });
}

// Handle login form submission
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('login_email').value;
        const password = document.getElementById('login_password').value;
        const errorDiv = document.getElementById('loginError');
        const errorText = document.getElementById('loginErrorText');
        
        // Hide error by default
        errorDiv.classList.add('hidden');
        
        fetch('/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                email: email,
                password: password,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message && data.message.includes('successful')) {
                closeModal('loginModal');
                window.location.reload();
            } else {
                errorText.textContent = data.message || 'Login failed';
                errorDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            errorText.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
            console.error('Error:', error);
        });
    });
}

// Handle register form submission
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('register_name').value;
        const email = document.getElementById('register_email').value;
        const password = document.getElementById('register_password').value;
        const passwordConfirm = document.getElementById('register_password_confirm').value;
        const errorDiv = document.getElementById('registerError');
        const errorText = document.getElementById('registerErrorText');
        
        // Hide error by default
        errorDiv.classList.add('hidden');
        
        if (password !== passwordConfirm) {
            errorText.textContent = 'Passwords do not match';
            errorDiv.classList.remove('hidden');
            return;
        }
        
        fetch('/auth/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                name: name,
                email: email,
                password: password,
                password_confirm: passwordConfirm,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message && data.message.includes('successful')) {
                closeModal('registerModal');
                window.location.reload();
            } else {
                errorText.textContent = data.message || 'Registration failed';
                errorDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            errorText.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
            console.error('Error:', error);
        });
    });
}

// Handle logout
const logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        showLogoutConfirm();
    });
}

// Close logout confirm modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.id === 'logoutConfirmModal') {
        closeLogoutConfirm();
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.id === 'loginModal' || event.target.id === 'registerModal') {
        closeModal(event.target.id);
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal('loginModal');
        closeModal('registerModal');
    }
});
