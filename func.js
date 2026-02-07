document.addEventListener('DOMContentLoaded', () => {
    // 1. Selectors
    const slotButtons = document.querySelectorAll('.slot-btn');
    const dateInput = document.getElementById('bookingDate'); 
    const proceedBtn = document.getElementById('proceedBtn'); 
    const sportSelect = document.getElementById('sportSelect'); // NEW: Added Sport Selector
    
    // Auth Elements
    const authForm = document.getElementById('authForm');
    const toggleBtn = document.getElementById('toggleAuth');
    const nameWrapper = document.getElementById('nameWrapper');
    const modalTitle = document.getElementById('modalTitle');
    const mainAuthBtn = document.getElementById('mainAuthBtn');

    let selectedSlot = null;
    let isLogin = true;

    // 2. Set minimum date to today
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }

    // 3. Handle Slot Selection
    slotButtons.forEach(button => {
        button.addEventListener('click', function() {
            slotButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-success');
                btn.classList.add('btn-outline-success');
            });
            this.classList.remove('btn-outline-success');
            this.classList.add('btn-success', 'active');
            selectedSlot = this.innerText;
        });
    });

    // 4. Handle Proceed to Payment (Booking)
    if (proceedBtn) {
        proceedBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            const selectedDate = dateInput.value;
            const selectedSport = sportSelect ? sportSelect.value : null; // NEW: Capture Sport

            // Validation
            if (!selectedSport) {
                alert("Please select a sport.");
                return;
            }
            if (!selectedDate) {
                alert("Please select a date.");
                return;
            }
            if (!selectedSlot) {
                alert("Please choose a time slot.");
                return;
            }

            // Send data to book.php
            fetch('book.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    date: selectedDate,
                    slot: selectedSlot,
                    sport: selectedSport, // NEW: Added sport to the request
                    turf: "TurfLegends Arena"
                })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.status === 'success' ? "🎉 " + data.message : "❌ " + data.message);
                if (data.status === 'success') window.location.reload();
            })
            .catch(err => {
                console.error("Booking Error:", err);
                alert("An error occurred during booking.");
            });
        });
    }

    // 5. Auth Toggle Logic
    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            isLogin = !isLogin;
            
            if (isLogin) {
                modalTitle.innerText = "Login to TurfLegends";
                mainAuthBtn.innerText = "Sign In";
                toggleBtn.innerText = "New user? Create Account";
                nameWrapper.classList.add('d-none');
            } else {
                modalTitle.innerText = "Create Your Account";
                mainAuthBtn.innerText = "Create Account";
                toggleBtn.innerText = "Already have an account? Login";
                nameWrapper.classList.remove('d-none');
            }
        });
    }

    // 6. Auth Submission
    if (authForm) {
        authForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const authData = {
                action: isLogin ? 'login' : 'signup',
                name: document.getElementById('authName') ? document.getElementById('authName').value : "",
                email: document.getElementById('authEmail').value,
                password: document.getElementById('authPassword').value
            };

            fetch('auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(authData)
            })
            .then(res => res.json())
            .then(data => {
                alert(data.status === 'success' ? "✅ " + data.message : "❌ " + data.message);
                if (data.status === 'success') {
                    if (isLogin) window.location.reload();
                    else toggleBtn.click();
                }
            })
            .catch(err => {
                console.error("Auth Error:", err);
                alert("An error occurred during authentication.");
            });
        });
    }
}); 