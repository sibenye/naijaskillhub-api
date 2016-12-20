<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\EmailTransactionalSendRequest;

/**
 * EmailTransactionalSendRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class EmailTransactionalSendRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return EmailTransactionalSendRequest
     */
    public function map($in)
    {
        $out = new EmailTransactionalSendRequest();

        $out->setFrom(array_get($in, 'from', NULL));
        $out->setTo(array_get($in, 'to', NULL));
        $out->setSubject(array_get($in, 'subject', NULL));
        $out->setContent(array_get($in, 'content', NULL));
        $out->setContentType(array_get($in, 'contentType', 'HTML'));
        $out->setCc(array_get($in, 'cc', NULL));
        $out->setBcc(array_get($in, 'bcc', NULL));

        return $out;
    }
}