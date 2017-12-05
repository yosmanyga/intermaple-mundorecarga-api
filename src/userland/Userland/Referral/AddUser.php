<?php

namespace Intermaple\Mundorecarga\Userland\Referral;

/**
 * @di\service()
 */
class AddUser
{
    /**
     * @var SelectUserCollection
     */
    private $selectUserCollection;

    /**
     * @param SelectUserCollection $selectUserCollection
     */
    public function __construct(
        SelectUserCollection $selectUserCollection
    ) {
        $this->selectUserCollection = $selectUserCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/referral/add-user"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     */
    public function add(
        string $client
    ) {
        $this->selectUserCollection->select()->insertOne([
            '_id' => $client,
            'code' => $this->generateCode(),
            'balance' => 10, // Initial promo
            'referrals' => []
        ]);
    }

    /**
     * @return string
     */
    private function generateCode()
    {
        $numbers = [2, 3, 4, 5, 6, 7, 8, 9];
        $letters = ['A', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'X', 'Z'];

        $c = 0;
        do {
            $c++;

            $code = sprintf(
                '%s%s%s%s',
                $letters[rand(0, count($letters) - 1)],
                $numbers[rand(0, count($numbers) - 1)],
                $letters[rand(0, count($letters) - 1)],
                $numbers[rand(0, count($numbers) - 1)]
            );

            $user = $this->selectUserCollection->select()->findOne([
                'code' => $code
            ]);

            // Problem finding new code?
            if ($c > 9999) {
                throw new \LogicException();
            }
        } while ($user);

        return $code;
    }
}