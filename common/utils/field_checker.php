<?php


function checkFields($assocArray, $fieldConfig)
{

    foreach ($fieldConfig as $field) {
        if (empty($assocArray[$field])) {
            http_response_code(400);
            echo json_encode(array('message' => "Field $field is required", 'code' => "MISSING_FIELD"));
            die();
        }
    }
}
