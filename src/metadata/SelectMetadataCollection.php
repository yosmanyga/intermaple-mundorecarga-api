<?php

namespace Intermaple\Mundorecarga;

use MongoDB\Client;
use MongoDB\Collection;

/**
 * @di\service({
 *     private: true
 * })
 */
class SelectMetadataCollection
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var
     */
    private $db;

    /**
     * @di\arguments({
     *     uri: "%mongo_uri%",
     *     db:  "%mongo_db%"
     * })
     *
     * @param string $uri
     * @param string $db
     */
    public function __construct(string $uri, string $db)
    {
        $this->uri = $uri;
        $this->db = $db;
    }

    /**
     * @return Collection
     */
    public function select()
    {
        return (new Client($this->uri))
            ->selectCollection(
                $this->db,
                'metadatas',
                [
                    'typeMap' => array(
                        'root' => Metadata::class,
                    ),
                ]
            );
    }
}
