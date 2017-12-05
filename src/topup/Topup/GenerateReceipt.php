<?php

namespace Intermaple\Mundorecarga\Topup;

use Dompdf\Dompdf;
use Intermaple\Mundorecarga\NonexistentProductException;
use Intermaple\Mundorecarga\NonexistentProviderException;
use Intermaple\Mundorecarga\PickContact;
use Intermaple\Mundorecarga\PickProduct;
use Intermaple\Mundorecarga\PickProvider;
use Intermaple\Mundorecarga\PickTopup;
use Yosmy\Country;
use Yosmy\Userland;

/**
 * @di\service()
 */
class GenerateReceipt
{
    /**
     * @var PickTopup
     */
    private $pickTopup;

    /**
     * @var PickProduct
     */
    private $pickProduct;

    /**
     * @var Country\ResolveName
     */
    private $resolveCountryName;

    /**
     * @var PickProvider
     */
    private $pickProvider;

    /**
     * @var PickContact
     */
    private $pickContact;

    /**
     * @var Userland\Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var Userland\Stripe\RetrieveCharge
     */
    private $retrieveCharge;

    /**
     * @param PickTopup $pickTopup
     * @param PickProduct $pickProduct
     * @param Country\ResolveName $resolveCountryName
     * @param PickProvider $pickProvider
     * @param PickContact $pickContact
     * @param Userland\Phone\PickUser $pickPhoneUser
     * @param Userland\Stripe\RetrieveCharge $retrieveCharge
     */
    public function __construct(PickTopup $pickTopup, PickProduct $pickProduct, Country\ResolveName $resolveCountryName, PickProvider $pickProvider, PickContact $pickContact, Userland\Phone\PickUser $pickPhoneUser, Userland\Stripe\RetrieveCharge $retrieveCharge)
    {
        $this->pickTopup = $pickTopup;
        $this->pickProduct = $pickProduct;
        $this->resolveCountryName = $resolveCountryName;
        $this->pickProvider = $pickProvider;
        $this->pickContact = $pickContact;
        $this->pickPhoneUser = $pickPhoneUser;
        $this->retrieveCharge = $retrieveCharge;
    }

    /**
     * @http\resolution({method: "POST", path: "/topup/generate-receipt"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $topup
     *
     * @return string
     */
    public function generate(
        string $topup
    ) {
        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', 'landscape');

        $html = $this->prepare($topup);

        $dompdf->loadHtml($html);
        $dompdf->render();

        return base64_encode($dompdf->output());
    }

    /**
     * @param string $topup
     *
     * @return string
     */
    private function prepare($topup)
    {
        $topup = $this->pickTopup->pick($topup);

        try {
            $product = $this->pickProduct->pick($topup->getProduct());
        } catch (NonexistentProductException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $provider = $this->pickProvider->pick(null, $product->getId());
        } catch (NonexistentProviderException $e) {
            throw new \LogicException(null, null, $e);
        }

        $contact = $this->pickContact->pick($topup->getContact(), null);

        try {
            $user = $this->pickPhoneUser->pick($contact->getUser(), null, null, null);
        } catch (Userland\Phone\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $country = $this->resolveCountryName->resolve($user->getCountry())->getValue();
        } catch (Country\NotFoundException $e) {
            throw new \LogicException(null, null, $e);
        }

        $from = [
            'country' => $country,
            'number' => sprintf('+%s-%s', $user->getPrefix(), $user->getNumber())
        ];

        try {
            $country = $this->resolveCountryName->resolve($contact->getCountry())->getValue();
        } catch (Country\NotFoundException $e) {
            throw new \LogicException(null, null, $e);
        }

        $to = [
            'country' => $country,
            'provider' => $provider->getName(),
            'number' => sprintf('+%s-%s', $contact->getPrefix(), $contact->getAccount())
        ];

        $charge = $this->retrieveCharge->retrieve($topup->getStripe());

        $mustache = new \Mustache_Engine(
            [
                'loader' => new \Mustache_Loader_FilesystemLoader(
                    __DIR__.'/templates',
                    [
                        'extension' => ''
                    ]
                )
            ]
        );

        return $mustache->render(
            'receipt.mustache',
            [
                'topup' => [
                    'from' => $from,
                    'to' => $to,
                    'amount' => $topup->getAmount(),
                    'date' => date('M d, Y', $topup->getDate()),
                    'transfer' => [
                        'id' => $topup->getDing(),
                    ],
                    'payment' => [
                        'fee' => $topup->getFee(),
                        'total' => $topup->getCharge(),
                        'card' => [
                            'name' => $charge->getName(),
                            'last4' => $charge->getLast4(),
                            'funding' => $charge->getFunding(),
                            'brand' => $charge->getBrand(),
                        ]
                    ]
                ],
            ]
        );
    }
}
