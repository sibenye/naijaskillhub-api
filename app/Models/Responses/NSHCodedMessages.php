<?php
/**
 * @package App\Models\Responses
 */
namespace App\Models\Responses;

class NSHCodedMessages
{
    const messages = [
            0 => "Success",
            100 => "Resource Not Found",
            101 => "User Must Be Authenticated",
            102 => "User Not Authorized To Perform Action",
            110 => "User must have accountType of 'talent'",
            111 => "Invalid Request",
            190 => "Error Connecting to the Database"
    ];
}
