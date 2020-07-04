<?php
require('../Class/MailService.php');
require('../Class/Utilities.php');

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

    //Rename uploaded file with prepending timestamp
    $filename = basename($fileName);
    $currentDateTime = new DateTime();
    $timePrefix = $currentDateTime->format('Y-m-d-s-i-H');
    $newFileName =  "./uploads/{$timePrefix}-{$fileName}";
    rename("./uploads/{$fileName}", $newFileName);
}

$mailService = new MailService();
$recipients = [
    $_POST['recipient1'] => $_POST['email']
];
$mailService->setRecipientsInfo($recipients);

if (isset($_FILES['file1']['name'])) {
    $attachments = [
        $fileName => $newFileName
    ];
    $mailService->addAttachments($attachments);
}

$mailService->setEmailDetails(
    $_POST['subject'],
    $_POST['body'],
    $_POST['alt-body']
);

$result = $mailService->sendEmail();
Utilities::deliverResponse(200, $result);