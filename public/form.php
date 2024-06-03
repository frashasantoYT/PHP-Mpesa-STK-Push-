<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mpesa Stk-Push Form</title>
    <link rel="stylesheet" href="style.css">
    <style>
      
        .loader {
            border: 5px solid #f3f3f3; 
            border-top: 5px solid #3498db; 
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 20px auto; 
            display: block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form id="stkPushForm" action="index.php" method="POST">
            <h2>Mpesa STK Push</h2>
            <div class="input-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" placeholder="2547XXXXXXXX or 07XXXXXXXX" required>
                <span class="error-message" id="phone-error"></span>
            </div>
            <div class="input-group">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required min="1" placeholder="Amount">
            </div>
            <button type="submit" class="submit-button">Submit</button>
            <div id="loader" class="loader" style="display: none;"></div> 
            <div id="success-message" class="success-message" style="display: none;">
                <p>STK Push request sent successfully!</p>
                <pre id="response-details"></pre>
            </div>
            <div id="error-message" class="error-message" style="display: none;">
                <p>Failed to send STK Push request.</p>
                <pre id="error-details"></pre>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('stkPushForm').addEventListener('submit', function(event) {
            const phoneInput = document.getElementById('phone');
            const phoneValue = phoneInput.value.trim();
            const phoneError = document.getElementById('phone-error');
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            const responseDetails = document.getElementById('response-details');
            const errorDetails = document.getElementById('error-details');
            const loader = document.getElementById('loader'); 

            const isValidPhone = /^(2547\d{8}|07\d{8})$/.test(phoneValue);
            if (!isValidPhone) {
                phoneError.textContent = 'Please enter a valid Kenyan phone number.';
                event.preventDefault();
                return;
            }

            phoneError.textContent = '';

            
            if (phoneValue.startsWith('07')) {
                phoneInput.value = '254' + phoneValue.substring(1);
            }

            // Show loader when form is submitted
            loader.style.display = 'block';

           
            event.preventDefault();
            const formData = new FormData(this);
            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.style.display = 'block';
                    errorMessage.style.display = 'none';
                    responseDetails.textContent = JSON.stringify(data.response, null, 2);
                } else {
                    successMessage.style.display = 'none';
                    errorMessage.style.display = 'block';
                    errorDetails.textContent = JSON.stringify(data.response, null, 2);
                }
            })
            .catch(error => {
                successMessage.style.display = 'none';
                errorMessage.style.display = 'block';
                errorDetails.textContent = 'An error occurred. Please try again.';
            })
            .finally(() => {
                // Hide loader when response is received or if there's an error
                loader.style.display = 'none';
            });
        });
    </script>
</body>
</html>
