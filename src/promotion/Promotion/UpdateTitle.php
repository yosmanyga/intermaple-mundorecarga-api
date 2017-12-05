<?php

namespace Intermaple\Mundorecarga\Promotion;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class UpdateTitle
{
    /**
     * @var Recharge\Ding\Promotion\UpdateTitle
     */
    private $updatePromotion;

    /**
     * @param Recharge\Ding\Promotion\UpdateTitle $updatePromotion
     */
    public function __construct(Recharge\Ding\Promotion\UpdateTitle $updatePromotion)
    {
        $this->updatePromotion = $updatePromotion;
    }

    /**
     * @http\resolution({method: "POST", path: "/promotion/update-title"})
     *
     * @param string $id
     * @param string $title
     */
    public function collect($id, $title)
    {
        $this->updatePromotion->update($id, $title);
    }
}
