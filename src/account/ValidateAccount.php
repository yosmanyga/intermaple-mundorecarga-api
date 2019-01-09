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
     * @param string $type
     *
     * @throws InvalidAccountException
     */
    public function validate($account, $type)
    {
        if (!$account) {
            throw new InvalidAccountException();
        }

//        if ($type == "phone") {
//            if (!ctype_digit($account)) {
//                throw new InvalidAccountException();
//            }
//        } else {
//            if (!$account) {
//                throw new InvalidAccountException();
//            }
//        }
    }
}
