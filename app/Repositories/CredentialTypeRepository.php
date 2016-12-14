<?php
/**
 * @package App\Repositories.
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * CredentialType Repository.
 * @author silver.ibenye
 *
 */
class CredentialTypeRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\CredentialType';
    }

    /**
     *
     * @param string $credentialTypeName
     * @return Model
     */
    public function getCredentialTypeByName($credentialTypeName)
    {
        return $this->model->where('name', $credentialTypeName)->first();
    }
}
