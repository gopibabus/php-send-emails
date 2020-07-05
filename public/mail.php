<?php
require('../Class/MailService.php');
require('../Class/Utilities.php');

//If file attachments are present then move them to uploads directory
if (isset($_FILES['file1']['name'])) {
    $currentDirectory = getcwd();
    $uploadDirectory = "/uploads/";
    Utilities::emptyDirectory($currentDirectory . $uploadDirectory);

    $fileName = $_FILES['file1']['name'];
    $fileTmpName  = $_FILES['file1']['tmp_name'];

    $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName);

    if (isset($_POST)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
        if (!$didUpload) {
            Utilities::deliverResponse(500, "An error occurred. Please contact the administrator.");
        }
    }
}

$mailService = new MailService();

//Add Recipients Info
$recipients = [
    $_POST['recipient1'] => $_POST['email']
];
$mailService->setRecipientsInfo($recipients);

//Add Attachments to email
if (isset($_FILES['file1']['name'])) {
    $attachments = [
        $fileName => $uploadPath
    ];
    $mailService->addAttachments($attachments);
}

//Add Subject and Body
$mailService->setEmailDetails(
    $_POST['subject'],
    $_POST['body'],
    $_POST['alt-body']
);

//Send Email
$result = $mailService->sendEmail();
Utilities::deliverResponse(200, $result);