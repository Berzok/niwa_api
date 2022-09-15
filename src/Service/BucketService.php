<?php

namespace App\Service;

use App\Entity\Resource;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Exception;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class BucketService {

    private S3Client $client;

    public function __construct() {
    }

    private function connect(): void {
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => 'ams3',
            'endpoint' => $_ENV['DO_ENDPOINT'],
            'credentials' => [
                'key' => $_ENV['DO_KEY_ACCESS'],
                'secret' => $_ENV['DO_SECRET'],
            ],
            'http' => [
                'verify' => env('APP_ENV') == 'dev'
            ]
        ]);
    }

    /**
     * @throws Exception
     */
    private function disconnect(): void {
        try {
            unset($this->client);
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * Delete a resource from the bucket it is stored in
     * @param Resource $resource
     * @return bool
     * @throws Exception
     */
    public function delete(Resource $resource): bool {
        $this->connect();
        try {
            $this->client->deleteObject([
                'Bucket' => $_ENV['DO_SPACE'],
                'Key' => $resource->getFilename(),
            ]);
        } catch (S3Exception $e) {
            var_dump($e);
        }

        $this->disconnect();
        return true;
    }

    /**
     * @throws Exception
     */
    public function upload(Resource $resource): void {
        $this->connect();
        try {
            $this->client->putObject([
                'Bucket' => $_ENV['DO_SPACE'],
                'Key' => $resource->getFilename(),
            ]);
        } catch (S3Exception $e) {
            var_dump($e);
        }
        $this->disconnect();
    }

    /**
     * Create a presigned URL for a given item in the Bucket
     * @param string $key - The key of the item
     * @param int $lifetime - Duration in minutes for which the created URL is valid. Evaluated using strtotime()
     * @return string - Presigned URL to $key valid for $lifetime
     */
    public function createUrl(string $key, int $lifetime): string {
        $cmd = $this->client->getCommand('GetObject', [
            'Bucket' => $_ENV['DO_SPACE'],
            'Key' => $key
        ]);

        $request = $this->client->createPresignedRequest($cmd, '+' . $lifetime . ' minutes');
        return (string)$request->getUri();
    }

    public function getAll(): array {
        $objects = $this->client->listObjects([
            'Bucket' => $_ENV['DO_SPACE']
        ]);

        $data = [];
        foreach ($objects['Contents'] as $obj) {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $_ENV['DO_SPACE'],
                'Key' => $obj['Key']
            ]);

            $request = $this->client->createPresignedRequest($cmd, '+25 minutes');
            $presignedUrl = (string)$request->getUri();

            $obj['url'] = $presignedUrl;
            $data[] = $obj;
        }

        return $data;
    }
}