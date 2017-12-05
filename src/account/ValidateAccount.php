<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class ValidateAccount
{
    /**
     * @http\resolution({method: "POST", path: "/validate-account"})
     *
     * @param string $account
     *
     * @throws InvalidAccountException
     */
    public function validate($account)
    {
        if (!ctype_digit($account)) {
            throw new InvalidAccountException();
        }
    }
}
