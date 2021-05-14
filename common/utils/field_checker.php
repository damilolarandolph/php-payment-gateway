<?php


function checkFields($assocArray, $fieldConfig)
{

    foreach ($fieldConfig as $field) {
        if (empty($assocArray[$field])) {
            http_response_code(400);
            return array('message' => "Field $field is required", 'code' => "MISSING_FIELD", 'field' => $field);
        }
    }

    return true;
}
