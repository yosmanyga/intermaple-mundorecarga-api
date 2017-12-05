<?php

namespace Intermaple\Mundorecarga\Blacklist\Log;

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
     * @http\resolution({method: "POST", path: "/blacklist/log/collect-events"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param int $from
     * @param int $to
     * @param int $limit
     *
     * @return Log\Events
     */
    public function collect(
        ?int $from,
        ?int $to,
        ?int $limit
    ) {
        $events = new Log\Events(
            $this->collectEvents->collect(
                [
                    new Log\Event\Label('type', 'yosmy.userland.blacklist.ban_session'),
                    new Log\Event\Label('type', 'yosmy.userland.blacklist.ban_user'),
                    new Log\Event\Label('type', 'yosmy.userland.stripe.blacklist.ban_card'),
                    new Log\Event\Label('type', 'blacklist.ban_contact'),
                ],
                $from,
                $to,
                $limit
            )->getIterator()
        );

        return $events;
    }
}