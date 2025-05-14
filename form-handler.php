<?php
/**
 * Memories and More Photo Booths - Form Handler
 * This script processes form submissions from contact.html and inquire.html
 * with improved validation and security
 */

// Set headers to prevent caching and allow CORS
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Initialize response array
$response = array(
    'success' => false,
    'message' => 'An error occurred while processing your request.'
);

/**
 * Sanitize user input
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 * @param string $email Email to validate
 * @return bool Whether email is valid
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate required fields
 * @param array $fields Array of field names to check
 * @param array $data Data array to check against
 * @return array [isValid, missingFields]
 */
function validateRequiredFields($fields, $data) {
    $missingFields = [];
    
    foreach ($fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $missingFields[] = $field;
        }
    }
    
    return [
        'isValid' => empty($missingFields),
        'missingFields' => $missingFields
    ];
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form type (contact or inquire)
    $formType = isset($_POST['form_type']) ? sanitizeInput($_POST['form_type']) : 'unknown';
    
    // Recipient email address
    $to = 'info@memoriesandmorephotobooths.com';
    
    // Set subject based on form type
    if ($formType === 'contact') {
        $subject = isset($_POST['subject']) ? 'Contact Form: ' . sanitizeInput($_POST['subject']) : 'New Contact Form Submission';
    } else if ($formType === 'inquire') {
        $subject = 'New Quote Request from Website';
    } else {
        $subject = 'New Form Submission';
    }
      // Process the form data
    $message = "";
    $headers = "";
      
    // Process test form
    if ($formType === 'test') {
        // Define required fields
        $requiredFields = ['name', 'email', 'message'];
        
        // Validate required fields
        $validation = validateRequiredFields($requiredFields, $_POST);
        if (!$validation['isValid']) {
            $response['success'] = false;
            $response['message'] = 'Please fill in all required fields: ' . implode(', ', $validation['missingFields']);
            echo json_encode($response);
            exit;
        }
        
        // Get and sanitize form data
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $messageContent = sanitizeInput($_POST['message']);
        
        // Validate email format
        if (!isValidEmail($email)) {
            $response['success'] = false;
            $response['message'] = 'Please enter a valid email address.';
            echo json_encode($response);
            exit;
        }
        
        // Create email message (HTML format)
        $message = "<html><body>";
        $message .= "<h2>Test Form Submission</h2>";
        $message .= "<p><strong>Name:</strong> " . $name . "</p>";
        $message .= "<p><strong>Email:</strong> " . $email . "</p>";
        $message .= "<p><strong>Message:</strong></p>";
        $message .= "<p>" . nl2br($messageContent) . "</p>";
        $message .= "</body></html>";
        
        // Set From address with proper formatting
        $fromName = preg_replace('/[^\p{L}\p{N}\s]/u', '', $name); // Remove special chars from name
        $fromEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // Set headers for HTML email
        $headers = "From: " . $fromName . " <noreply@memoriesandmorephotobooths.com>\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
        $headers .= "X-Sender: " . $fromEmail . "\r\n";
        
        // For testing, we'll just return success without actually sending email
        $response['success'] = true;
        $response['message'] = 'Test form submitted successfully! (Email sending skipped for testing)';
        echo json_encode($response);
        exit;
    }
    // Process contact form  
    if ($formType === 'contact') {
        // Define required fields
        $requiredFields = ['name', 'email', 'subject', 'message'];
        
        // Validate required fields
        $validation = validateRequiredFields($requiredFields, $_POST);
        if (!$validation['isValid']) {
            $response['success'] = false;
            $response['message'] = 'Please fill in all required fields: ' . implode(', ', $validation['missingFields']);
            echo json_encode($response);
            exit;
        }
        
        // Get and sanitize form data
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : 'Not provided';
        $messageContent = sanitizeInput($_POST['message']);
        
        // Validate email format
        if (!isValidEmail($email)) {
            $response['success'] = false;
            $response['message'] = 'Please enter a valid email address.';
            echo json_encode($response);
            exit;
        }
        
        // Create email message (HTML format)
        $message = "<html><body>";
        $message .= "<h2>Contact Form Submission</h2>";
        $message .= "<p><strong>Name:</strong> " . $name . "</p>";
        $message .= "<p><strong>Email:</strong> " . $email . "</p>";
        $message .= "<p><strong>Phone:</strong> " . $phone . "</p>";
        $message .= "<p><strong>Subject:</strong> " . $subject . "</p>";
        $message .= "<p><strong>Message:</strong></p>";
        $message .= "<p>" . nl2br($messageContent) . "</p>";
        $message .= "</body></html>";
        
        // Set From address with proper formatting
        $fromName = preg_replace('/[^\p{L}\p{N}\s]/u', '', $name); // Remove special chars from name
        $fromEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // Set headers for HTML email
        $headers = "From: " . $fromName . " <noreply@memoriesandmorephotobooths.com>\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
        $headers .= "X-Sender: " . $fromEmail . "\r\n";
    }    // Process inquire form
    else if ($formType === 'inquire') {
        // Define required fields for the inquire form 
        $requiredFields = ['name', 'email', 'phone', 'event_date', 'event_type'];
        
        // Validate required fields
        $validation = validateRequiredFields($requiredFields, $_POST);
        if (!$validation['isValid']) {
            $response['success'] = false;
            $response['message'] = 'Please fill in all required fields: ' . implode(', ', $validation['missingFields']);
            echo json_encode($response);
            exit;
        }
        
        // Step 1: Event Details - Get and sanitize
        $eventType = isset($_POST['event_type']) ? sanitizeInput($_POST['event_type']) : 'Not provided';
        $eventDate = isset($_POST['event_date']) ? sanitizeInput($_POST['event_date']) : 'Not provided';
        $eventTime = isset($_POST['event_time']) ? sanitizeInput($_POST['event_time']) : 'Not provided';
        $eventHours = isset($_POST['hours_needed']) ? sanitizeInput($_POST['hours_needed']) : 'Not provided';
        $eventVenue = isset($_POST['event_venue']) ? sanitizeInput($_POST['event_venue']) : 'Not provided';
        $eventAddress = isset($_POST['event_address']) ? sanitizeInput($_POST['event_address']) : 'Not provided';
        $guestCount = isset($_POST['guest_count']) ? sanitizeInput($_POST['guest_count']) : 'Not provided';
        
        // Step 2: Package Selection - Get and sanitize
        $boothType = isset($_POST['booth_type']) ? sanitizeInput($_POST['booth_type']) : 'Not selected';
        $backdrop = isset($_POST['backdrop']) ? sanitizeInput($_POST['backdrop']) : 'No preference';
        
        // Step 3: Contact Information - Get and sanitize
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $additionalInfo = isset($_POST['additional_info']) ? sanitizeInput($_POST['additional_info']) : 'None';
        
        // Validate email format
        if (!isValidEmail($email)) {
            $response['success'] = false;
            $response['message'] = 'Please enter a valid email address.';
            echo json_encode($response);
            exit;
        }
        
        // Validate date format if provided
        if (!empty($eventDate) && strtotime($eventDate) === false) {
            $response['success'] = false;
            $response['message'] = 'Please enter a valid event date.';
            echo json_encode($response);
            exit;
        }
        
        // Create email message (HTML format)
        $message = "<html><body>";
        $message .= "<h2>New Quote Request</h2>";
        
        // Event Details Section
        $message .= "<h3>Event Details</h3>";
        $message .= "<p><strong>Event Type:</strong> " . $eventType . "</p>";
        $message .= "<p><strong>Event Date:</strong> " . $eventDate . "</p>";
        $message .= "<p><strong>Event Time:</strong> " . $eventTime . "</p>";
        $message .= "<p><strong>Hours Needed:</strong> " . $eventHours . "</p>";
        $message .= "<p><strong>Venue Name:</strong> " . $eventVenue . "</p>";
        $message .= "<p><strong>Venue Address:</strong> " . $eventAddress . "</p>";
        $message .= "<p><strong>Guest Count:</strong> " . $guestCount . "</p>";
        
        // Booth Preferences Section
        $message .= "<h3>Booth Preferences</h3>";
        $message .= "<p><strong>Booth Type:</strong> " . $boothType . "</p>";
        $message .= "<p><strong>Backdrop:</strong> " . $backdrop . "</p>";
        
        // Contact Information Section
        $message .= "<h3>Contact Information</h3>";
        $message .= "<p><strong>Name:</strong> " . $name . "</p>";
        $message .= "<p><strong>Email:</strong> " . $email . "</p>";
        $message .= "<p><strong>Phone:</strong> " . $phone . "</p>";
        $message .= "<p><strong>Additional Information:</strong></p>";
        $message .= "<p>" . nl2br($additionalInfo) . "</p>";
        
        $message .= "</body></html>";
        
        // Set From address with proper formatting
        $fromName = preg_replace('/[^\p{L}\p{N}\s]/u', '', $name); // Remove special chars from name
        $fromEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // Set headers for HTML email
        $headers = "From: " . $fromName . " <noreply@memoriesandmorephotobooths.com>\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
        $headers .= "X-Sender: " . $fromEmail . "\r\n";
    }
      // Add common headers for HTML email
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // For security, set a BCC to keep a copy (optional)
    // $headers .= "Bcc: backup@memoriesandmorephotobooths.com\r\n";
      // Enable more detailed error reporting for testing
    // Comment out or remove these lines in production
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // Try to send the email with enhanced error handling
    try {
        // Save mail parameters for debugging
        $debugInfo = [
            'to' => $to,
            'subject' => $subject,
            'headers' => $headers,
            'message_length' => strlen($message),
            'php_version' => phpversion(),
            'smtp_server' => ini_get('SMTP'),
            'smtp_port' => ini_get('smtp_port'),
            'sendmail_from' => ini_get('sendmail_from')
        ];
          $mailSent = mail($to, $subject, $message, $headers);
        
        // Send confirmation email to customer
        $customerSubject = "Your Inquiry - Memories and More Photo Booths";
        $customerMessage = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e1e1; border-radius: 5px;'>
                <h2 style='color: #e91e63;'>Thank You for Your Interest!</h2>
                <p>Dear " . htmlspecialchars($name) . ",</p>
                <p>Thanks for reaching out to Memories and More Photo Booths. We've received your inquiry and a team member will contact you shortly to confirm availability for your event.</p>
                <p><strong>Event Date:</strong> " . htmlspecialchars($eventDate ?? 'Not specified') . "</p>
                <p>If you have any immediate questions, please feel free to call us at <strong>763.785.1590</strong>.</p>
                <p>Best regards,<br>Memories and More Photo Booths Team</p>
            </div>
        </body>
        </html>";
        
        // Set headers for customer email
        $customerHeaders = "MIME-Version: 1.0" . "\r\n";
        $customerHeaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $customerHeaders .= "From: Memories and More Photo Booths <no-reply@memoriesandmorephotobooths.com>" . "\r\n";
        
        // Send confirmation email to customer
        $customerMailSent = mail($email, $customerSubject, $customerMessage, $customerHeaders);
        
        // Log customer email status
        if ($customerMailSent) {
            error_log("Confirmation email sent to customer: $email");
        } else {
            error_log("Failed to send confirmation email to customer: $email");
        }
        
        // Check if mail was sent successfully
        if ($mailSent) {
            $response['success'] = true;
            $response['message'] = 'Your message has been sent successfully.';
            
            // Log successful submission
            error_log("Form submission successful. Form type: $formType, From: $email");
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to send message. Please try again later. Check server configuration.';
            
            // Add debug info
            $lastError = error_get_last();
            $response['debug_info'] = [
                'last_error' => $lastError ? $lastError['message'] : 'No specific error reported',
                'mail_config' => $debugInfo
            ];
            
            // Log error for debugging
            error_log("Form submission failed. Form type: $formType, PHP mail() returned false. Error: " . 
                      ($lastError ? $lastError['message'] : 'None'));
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'An error occurred: ' . $e->getMessage();
        
        // Include debug info in development environment
        $response['debug'] = [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
        
        // Log the actual error
        error_log("Form submission exception: " . $e->getMessage());
    }
}

// Return the response as JSON
echo json_encode($response);
?>
