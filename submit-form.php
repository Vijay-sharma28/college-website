<?php
// Database connection
$host = 'localhost'; // Your database host (usually localhost)
$username = 'root'; // Your database username (default is 'root')
$password = ''; // Your database password (default is empty for XAMPP)
$dbname = 'college_admissions'; // The name of the database

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form input data
    $full_name = $_POST['full-name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $course = $_POST['course'];
    $previous_school = $_POST['previous-school'];
    $marks = $_POST['marks'];

    // Handle file upload (Resume)
    $resume = NULL;
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resume_dir = 'uploads/';
        $resume_name = basename($_FILES['resume']['name']);
        $resume_path = $resume_dir . $resume_name;
        
        // Move the uploaded file to the "uploads" directory
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path)) {
            $resume = $resume_path; // Save the file path in the database
        } else {
            echo "Error uploading resume.";
        }
    }

    // Insert the form data into the database
    $sql = "INSERT INTO applicants (full_name, dob, email, phone, address, city, state, zip, course, previous_school, marks, resume)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssds", $full_name, $dob, $email, $phone, $address, $city, $state, $zip, $course, $previous_school, $marks, $resume);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
