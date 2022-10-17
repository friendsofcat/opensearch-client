<?php declare(strict_types=1);

namespace OpenSearch\Laravel\Client;

use ErrorException;
use OpenSearch\Client;
use OpenSearch\ClientBuilder as BaseClientBuilder;

class ClientBuilder implements ClientBuilderInterface
{
    protected array $cache;

    public function default(): Client
    {
        $name = config('opensearch.client.default');

        if (!is_string($name)) {
            throw new ErrorException('Default connection name is invalid or missing.');
        }

        return $this->connection($name);
    }

    public function connection(string $name): Client
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $config = config('opensearch.client.connections.' . $name);

        if (!is_array($config)) {
            throw new ErrorException(sprintf(
                'Configuration for connection %s is invalid or missing.',
                $name
            ));
        }

        $client = BaseClientBuilder::fromConfig($config);
        $this->cache[$name] = $client;

        return $client;
    }
}
