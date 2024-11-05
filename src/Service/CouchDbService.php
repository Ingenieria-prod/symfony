<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CouchDbService
{
    private HttpClientInterface $client;
    private string $couchDbUrl;
    private string $username;
    private string $password;

    public function __construct(HttpClientInterface $httpClient, string $couchDbUrl, string $username, string $password)
    {
        $this->client = $httpClient;
        $this->couchDbUrl = $couchDbUrl;
        $this->username = $username;
        $this->password = $password;
    }

    public function findUser(array $query): array
    {
        $url = "{$this->couchDbUrl}/_users/_find";
        try {
            $response = $this->client->request('POST', $url, [
                'auth_basic' => [$this->username, $this->password],
                'json' => $query, 
                'headers' => [
                'Content-Type' => 'application/json'
            ],
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteUser(string $id, string $rev): array
    {
        // Construye la URL con el ID y la revisión
        $url = "{$this->couchDbUrl}/_users/{$id}?rev={$rev}";

        try {
            $response = $this->client->request('DELETE', $url, [
                'auth_basic' => [$this->username, $this->password]
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function newUser($data): array
    {
        $url = "{$this->couchDbUrl}/_users/org.couchdb.user:{$data['name']}";      
        try {
            $response = $this->client->request('PUT', $url, [
                'auth_basic' => [$this->username, $this->password],
                'json' => $data,
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function editUser($data): array
    {
        $url = "{$this->couchDbUrl}/_users/{$data['_id']}?conflicts=true";     
        try {
            $response = $this->client->request('PUT', $url, [
                'auth_basic' => [$this->username, $this->password],
                'json' => $data,
            ]);
            return $response->toArray();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

