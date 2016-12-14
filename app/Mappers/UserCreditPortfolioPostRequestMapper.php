<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserCreditPortfolioPostRequest;

/**
 * UserCreditPortfolioPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserCreditPortfolioPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserCreditPortfolioPostRequest
     */
    public function map($in)
    {
        $out = new UserCreditPortfolioPostRequest();

        $out->setCreditId(array_get($in, 'creditId', NULL));
        $out->setCreditType(array_get($in, 'creditType', NULL));
        $out->setYear(array_get($in, 'year', NULL));
        $out->setCaption(array_get($in, 'caption', NULL));

        return $out;
    }
}
