<?php

namespace Controller;

class CompanyClass
{

    public function handleCompanyData(array $inputData): ?array
    {
        // Initialize company data array
        $companyData = [];

        // Validate company data
        if ($this->isCompanyDataValid($inputData)) {
            return $inputData;
        } else {
            // $this->setErrorMessage("Company data is not valid!");
        }

        $companyData['name'] = strtolower(trim($inputData['name']));

        // Validate website and address
        $this->validateWebsiteAndAddress($inputData, $companyData);

        return $companyData;
    }

    // Using &$companyData so the function can change the original array directly
    // instead of just working with a copy of it
    private function validateWebsiteAndAddress(array $inputData, array &$companyData)
    {
        // Validate website URL
        if (isset($inputData['website']) && !empty($inputData['website'])) {
            $websiteUrl = $inputData['website'];
            $companyData['website'] = preg_match('/http?:\/\//i', $websiteUrl) ? parse_url($websiteUrl, PHP_URL_HOST) : $websiteUrl;
        } else {
            // $this->setErrorMessage("No website URL found");
            $companyData['website'] = "No website URL found";
        }

        // Validate address
        if (isset($inputData['address']) && !empty($inputData['address'])) {
            $companyData['address'] = trim($inputData['address']);
        } else {
            // $this->setErrorMessage("No address found!");
            $companyData['address'] = "No address found!";
        }
    }

    // Validation for company name and address
    private function isCompanyDataValid(array $inputData): bool
    {
        return isset($inputData['name']) && !empty(trim($inputData['name'])) && isset($inputData['address']) && !empty(trim($inputData['address']));
    }

    // Optional for feedback (still have some little issue see commented codes in files (task-1.php and CompanyController.php))
    // Set error message
    public function setErrorMessage($message)
    {
        $_SESSION['ERROR_MESSAGE'] = $message;
    }

    // Get error message
    public function getErrorMessage()
    {
        return isset($_SESSION['ERROR_MESSAGE']) ? $_SESSION['ERROR_MESSAGE'] : null;
    }

    // Unset error message
    public function unsetErrorMessage()
    {
        $_SESSION['ERROR_MESSAGE'] = null;
    }
}
