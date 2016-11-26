<?php

namespace App\Models\Requests;

interface IPostRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function getValidationRules();

}