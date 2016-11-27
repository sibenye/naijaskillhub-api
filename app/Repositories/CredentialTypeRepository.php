<?php

namespace App\Repositories;

class CredentialTypeRepository extends BaseRepository {

    public function model() {
        return 'App\Models\DAO\CredentialType';
    }

    public function getCredentialTypeByName($credentialTypeName) {
        return $this->model->where('name', $credentialTypeName)->first();
    }

}
