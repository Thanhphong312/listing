<?php

namespace Vanguard\Services\PushFileJsonToBackblaze;

use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class PushFileJsonToBackblazeService
{
    protected $accountId;
    protected $appId;
    protected $applicationKey;
    protected $authToken;
    protected $apiUrl;

    public function __construct()
    {
        $this->appId = env('B2_ACCESS_KEY_ID', null);
        $this->applicationKey = env('B2_SECRET_ACCESS_KEY', null);
        $this->authorizeAccount();
    }

    private function authorizeAccount()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.backblazeb2.com/b2api/v2/b2_authorize_account', [
            'auth' => [$this->appId, $this->applicationKey]
        ]);
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            $this->authToken = $data['authorizationToken'];
            $this->apiUrl = $data['apiUrl'];
            $this->accountId = $data['accountId'];
        } else {
            throw new Exception("Authorization failed: " . $response->getBody());
        }
    }

    public function listFileVersions($fileName, $bucketId)
    {
        $client = new Client();
        $url = $this->apiUrl . '/b2api/v2/b2_list_file_versions';
        $headers = [
            'Authorization' => $this->authToken
        ];
        $data = [
            'bucketId' => $bucketId,
            'startFileName' => $fileName,
            'prefix' => $fileName
        ];
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception("Failed to list file versions: " . $response->getBody());
        }
    }

    public function deleteFileVersion($fileName, $fileId)
    {
        $client = new Client();
        $url = $this->apiUrl . '/b2api/v2/b2_delete_file_version';
        $headers = [
            'Authorization' => $this->authToken
        ];
        $data = [
            'fileName' => $fileName,
            'fileId' => $fileId
        ];

        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception("Failed to delete file version: " . $response->getBody());
        }
    }

    public function deleteAllVersionsByFileName($fileName, $bucketId)
    {
        $versions = $this->listFileVersions($fileName, $bucketId);

        if (isset($versions['files'])) {
            foreach ($versions['files'] as $file) {
                if ($file['fileName'] == $fileName) {
                    $this->deleteFileVersion($file['fileName'], $file['fileId']);
                }
            }
            return "All versions deleted successfully.";
        } else {
            throw new Exception("No versions found for the file: " . $fileName);
        }
    }
    public function listBuckets()
    {
        $client = new Client();
        $url = $this->apiUrl . '/b2api/v2/b2_list_buckets';
        $headers = [
            'Authorization' => $this->authToken
        ];
        $data = [
            'accountId' => $this->accountId,
            'bucketName' => 'Windycloud',
        ];

        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        } else {
            throw new Exception("Failed to list buckets: " . $response->getBody());
        }
    }

    public function getBucketIdByName($bucketName)
    {
        $buckets = $this->listBuckets();
        if (isset($buckets['buckets'])) {
            $filtered = array_filter($buckets['buckets'], fn($bucket) => $bucket['bucketName'] === $bucketName);
            $firstMatch = reset($filtered); 
            return $firstMatch ? $firstMatch['bucketId'] : null;
        }

        throw new Exception("Bucket with name '$bucketName' not found.");
    }
    
    public function pushFileToBlaze($image, $fileName, $bucket_name)
    {
        $bucketId = $this->getBucketIdByName($bucket_name);
        $client = new Client();
        $url = $this->apiUrl . '/b2api/v2/b2_get_upload_url';
        $headers = [
            'Authorization' => $this->authToken
        ];
        $data = [
            'bucketId' => $bucketId
        ];
    
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data
        ]);
    
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Failed to get upload URL: " . $response->getBody());
        }
    
        $uploadData = json_decode($response->getBody(), true);
        $uploadUrl = $uploadData['uploadUrl'];
        $uploadAuthToken = $uploadData['authorizationToken'];
    
        $fileContents = file_get_contents($image->getPathname()); 
        $fileSha1 = sha1_file($image->getPathname()); 
    
        $uploadResponse = $client->request('POST', $uploadUrl, [
            'headers' => [
                'Authorization' => $uploadAuthToken,
                'X-Bz-File-Name' => urlencode($fileName),
                'Content-Type' => 'b2/x-auto',
                'X-Bz-Content-Sha1' => $fileSha1
            ],
            'body' => $fileContents
        ]);
    
        if ($uploadResponse->getStatusCode() === 200) {
            return json_decode($uploadResponse->getBody(), true);
        } else {
            throw new \Exception("Failed to upload file: " . $uploadResponse->getBody());
        }
    }
    public function pushFileToBlazePdf($label, $fileName, $bucket_name)
    {
        $bucketId = $this->getBucketIdByName($bucket_name);
        $client = new Client();
        $url = $this->apiUrl . '/b2api/v2/b2_get_upload_url';
        $headers = [
            'Authorization' => $this->authToken
        ];
        $data = [
            'bucketId' => $bucketId
        ];
    
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data
        ]);
    
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Failed to get upload URL: " . $response->getBody());
        }
    
        $uploadData = json_decode($response->getBody(), true);
        $uploadUrl = $uploadData['uploadUrl'];
        $uploadAuthToken = $uploadData['authorizationToken'];
    
        $fileContents = file_get_contents($label); 
        $fileSha1 = sha1_file($label); 
    
        $uploadResponse = $client->request('POST', $uploadUrl, [
            'headers' => [
                'Authorization' => $uploadAuthToken,
                'X-Bz-File-Name' => urlencode($fileName),
                'Content-Type' => 'b2/x-auto',
                'X-Bz-Content-Sha1' => $fileSha1
            ],
            'body' => $fileContents
        ]);
    
        if ($uploadResponse->getStatusCode() === 200) {
            return json_decode($uploadResponse->getBody(), true);
        } else {
            throw new \Exception("Failed to upload file: " . $uploadResponse->getBody());
        }
    }
    
}
