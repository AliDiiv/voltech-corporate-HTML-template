<?php

$data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = "";
    $email = "";
    $subject = "";
    $comments = "";
    $recipient = "mimvahidi@gmail.com";

    // connection
    $servername = "localhost";
    $username = "user_name";
    $password = "password";
    $dbname = "database_name";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // connection
    if ($conn->connect_error) {
        $data = [
            'status' => 'Error',
            'message' => 'Database connection failed: ' . $conn->connect_error
        ];
        echo json_encode($data);
        exit;
    }

    if (isset($_POST['name'])) {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    }

    if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $data = [
                'status' => 'Error',
                'message' => 'The email address is not valid.'
            ];
            echo json_encode($data);
            exit;
        }
    }

    if (isset($_POST['subject'])) {
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    }

    if (isset($_POST['comments'])) {
        $comments = htmlspecialchars($_POST['comments']);
    }

    // inmport data
    $sql = "INSERT INTO emails (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$comments')";

    if ($conn->query($sql) === TRUE) {
        //Set the email header
        $headers = 'MIME-Version: 1.0' . "\r\n"
            . 'Content-type: text/html; charset=utf-8' . "\r\n"
            . 'From: ' . $email . "\r\n";

        //send mail
        if (mail($recipient, $subject, $comments, $headers)) {
            $data = [
                'status' => 'Success',
                'message' => 'Your message has been successfully sent.'
            ];
        } else {
            $data = [
                'status' => 'Error',
                'message' => 'Your message was not sent. Please try again.'
            ];
        }
    } else {
        $data = [
            'status' => 'Error',
            'message' => 'Error inserting data into database: ' . $conn->error
        ];
    }

    $conn->close();
} else {
    $data = [
        'status' => 'Error',
        'message' => 'The request is invalid.'
    ];
}

echo json_encode($data);
?>
