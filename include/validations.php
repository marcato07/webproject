<?php
    /**
     * Contains functions to validate and sanitize inputs
     */

    // Sanitize and validate the ID
    function validateIntInput($inputType, $inputName)
    {
        // Sanitize and validate the ID
        $input = filter_input($inputType, $inputName, FILTER_SANITIZE_NUMBER_INT);
        if (filter_var($input, FILTER_VALIDATE_INT) == false)
        {
            $input = null;
        }

        return $input;
    }
?>