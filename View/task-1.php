<?php

session_start();

require '../Controller/CompanyController.php';

use Controller\CompanyClass;

// Test Data
$input_1 = [
    'name' => ' OpenAI ',
    'website' => 'https://openai.com',
    'address' => ''
];

$input_2 = [
    'name' => 'Innovatiespotter',
    'address' => 'Groningen'
];

$input_3 = [
    'name' => ' Apple ',
    'website' => 'xhttps://apple.com '
];

// Instantiate CompanyClass
$company = new CompanyClass();


// Optional feedback (still have some little issues)
// $errorMessage = $company->getErrorMessage();

// $unsetMessage = $company->unsetErrorMessage();

if (!empty($errorMessage)) {
    echo "Error: " . $errorMessage . "<br><br>";
    $unsetMessage;
}

echo "</pre>";

// Display result(s)
$result_1 = $company->handleCompanyData($input_1);

$result_2 = $company->handleCompanyData($input_2);

$result_3 = $company->handleCompanyData($input_3);

print_r($result_1);
