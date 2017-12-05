<?php

namespace Intermaple\Mundorecarga\Topup;

use Yosmy\Log;

class Events extends Log\Events
{
    /**
     * @param \Traversable $cursor
     */
    public function __construct(
        \Traversable $cursor
    ) {
        parent::__construct(
            $cursor,
            Event::class
        );
    }
}
