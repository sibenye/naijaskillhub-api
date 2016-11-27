<?php

namespace App\Models\Requests;

interface IPostRequest {

    /**
     * Build Model attributes from request properties
     *
     * @return array
     */
    public function buildModelAttributes();

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function getValidationRules();

}