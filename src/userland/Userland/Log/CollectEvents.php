<?php

namespace Intermaple\Mundorecarga\Userland\Log;

use Yosmy\Log;

/**
 * @di\service()
 */
class CollectEvents
{
    /**
     * @var Log\CollectEvents
     */
    private $collectEvents;

    /**
     * @param Log\CollectEvents $collectEvents
     */
    public function __construct(Log\CollectEvents $collectEvents)
    {
        $this->collectEvents = $collectEvents;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/log/collect-events"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $user
     *
     * @return Log\Events
     */
    public function collect(
        $user
    ) {
        $events = new Log\Events(
            $this->collectEvents->collect(
                [
                    new Log\Event\Label('user', $user),
                ],
                null,
                null,
                null
            )->getIterator()
        );

        return $events;
    }
}