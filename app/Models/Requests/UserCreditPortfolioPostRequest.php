<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserCreditPortfolioPost Request.
 *
 * @author silver.ibenye
 *
 */
class UserCreditPortfolioPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $creditId;

    /**
     *
     * @var string
     */
    private $creditType;

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @var string
     */
    private $caption;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        $attr = array ();

        if (!empty($this->creditId)) {
            $attr ['creditId'] = $this->creditId;
        }

        $attr ['creditType'] = $this->creditType;
        $attr ['year'] = $this->year;
        $attr ['caption'] = $this->caption;

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     * @return array
     */
    public function getValidationRules()
    {
        return [
                'creditType' => 'required|max:45',
                'year' => 'required|max:4',
                'caption' => 'required|max:500'
        ];
    }

    /**
     *
     * @return array
     */
    public function getUpdateValidationRules()
    {
        return [
                'creditId' => 'required',
                'creditType' => 'max:45',
                'year' => 'max:4',
                'caption' => 'max:500'
        ];
    }

    /**
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     *
     * @param string $caption
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return the string
     */
    public function getCreditId()
    {
        return $this->creditId;
    }

    /**
     * @param  $creditId
     * @return void
     */
    public function setCreditId($creditId)
    {
        $this->creditId = $creditId;
    }

    /**
     * @return the string
     */
    public function getCreditType()
    {
        return $this->creditType;
    }

    /**
     * @param  $creditTypeId
     * @return void
     */
    public function setCreditType($creditType)
    {
        $this->creditType = $creditType;
    }

    /**
     * @return the string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param  $year
     * @return void
     */
    public function setYear($year)
    {
        $this->year = $year;
    }
}
