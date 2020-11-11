<?php
namespace PAMI\Message\Response\Factory;

use PAMI\Exception\PAMIException;
use PAMI\Message\Response\Response;

interface IResponseFactory
{
    /**
     * This is our factory method.
     *
     * @param string $responseMessage Literal message as received from ami.
     * @param string $requestMessage
     *
     * @return Response
     * @throws PAMIException
     */
    public function createFromRaw($responseMessage, $requestMessage = null);
}
