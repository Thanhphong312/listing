<?php

namespace Vanguard\Services\Blaze;

use Vanguard\Models\TimeLine;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class BlazeService
{
    protected $accountId;
    protected $appId;
    protected $applicationKey;
    protected $authToken;
    protected $apiUrl;

    public function __construct()
    {
        $this->appId = env('B2_ACCESS_KEY_ID');
        $this->applicationKey = env('B2_SECRET_ACCESS_KEY');
        // $this->appId = '00477a5b66869f10000000013';
        // $this->applicationKey = 'K004MqpBUDJg5jATHxdLX6KOm8bvVlAs';
        $this->authorizeAccount();
    }

    private function authorizeAccount()
    {
        // return 123;
        $client = new Client();
        $response = $client->request('GET', 'https://api.backblazeb2.com/b2api/v2/b2_authorize_account', [
            'auth' => [$this->appId, $this->applicationKey]
        ]);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            // dd($data);
            $this->authToken = $data['authorizationToken'];
            $this->apiUrl = $data['apiUrl'];
            $this->accountId = $data['accountId'];
        } else {
            throw new Exception("Authorization failed: " . $response->getBody());
        }
    }

    public function uploadFile($fileContent, $fileName, $bucketId)
    {
        // Step 1: Get Upload URL
        $client = new Client();
        $response = $client->request('POST', $this->apiUrl . '/b2api/v2/b2_get_upload_url', [
            'headers' => [
                'Authorization' => $this->authToken,
            ],
            'json' => [
                'bucketId' => $bucketId,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Failed to get upload URL: " . $response->getBody());
        }

        $uploadData = json_decode($response->getBody(), true);
        $uploadUrl = $uploadData['uploadUrl'];
        $uploadAuthToken = $uploadData['authorizationToken'];

        // Step 2: Upload the File
        $fileContent = file_get_contents($fileContent);
        $sha1Hash = sha1($fileContent);

        $uploadResponse = $client->request('POST', $uploadUrl, [
            'headers' => [
                'Authorization' => $uploadAuthToken,
                'X-Bz-File-Name' => $fileName,
                'Content-Type' => 'b2/x-auto',
                'X-Bz-Content-Sha1' => $sha1Hash,
            ],
            'body' => $fileContent,
        ]);

        if ($uploadResponse->getStatusCode() === 200) {
            return json_decode($uploadResponse->getBody(), true);
        } else {
            throw new Exception("Failed to upload file: " . $uploadResponse->getBody());
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
        //dd($data);
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
        // dd($this->accountId);
        $headers = [
            'Authorization' => $this->authToken
        ];
        $data = [
            'accountId' => $this->accountId,
            'bucketName' => 'felinepropduct',
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
            foreach ($buckets['buckets'] as $bucket) {
                if ($bucket['bucketName'] === $bucketName) {
                    return $bucket['bucketId'];
                }
            }
        }

        throw new Exception("Bucket with name '$bucketName' not found.");
    }

    public function pushTimelineToCloud($dataTimeline, $nameFile)
    {
        $dataTimeline = $this->getTimeline($dataTimeline, $nameFile);
        $bucketId = $this->getBucketIdByName('pressifypod');
        $this->deleteAllVersionsByFileName('timelines/orders/' . $nameFile, $bucketId);

        $fileContent = json_encode($dataTimeline);

        $directory = public_path('timelines/orders');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(public_path('timelines/orders/' . $nameFile), $fileContent);

        $sourceFilePath = public_path('timelines/orders/' . $nameFile);

        $filePath = Storage::disk('b2')->putFileAs('/timelines/orders', $sourceFilePath, $nameFile, 'public');

        $url = Storage::disk('b2')->temporaryUrl($filePath, '', []);

        $newUrl = substr($url, 0, strpos($url, '?X-Amz'));

        $filePathLocal = public_path('timelines/orders/' . $nameFile);

        if (file_exists($filePathLocal)) {
            File::delete($filePathLocal);
        }
    }
    public function pushTimelineTicketToCloud($dataTimeline, $nameFile)
    {
        $dataTimeline = $this->getTimelineTicket($dataTimeline, $nameFile);
        $bucketId = $this->getBucketIdByName('pressifypod');
        $this->deleteAllVersionsByFileName('timelines/tickets/' . $nameFile, $bucketId);

        $fileContent = json_encode($dataTimeline);

        $directory = public_path('timelines/tickets');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(public_path('timelines/tickets/' . $nameFile), $fileContent);

        $sourceFilePath = public_path('timelines/tickets/' . $nameFile);

        $filePath = Storage::disk('b2')->putFileAs('/timelines/tickets', $sourceFilePath, $nameFile, 'public');

        $url = Storage::disk('b2')->temporaryUrl($filePath, '', []);

        $newUrl = substr($url, 0, strpos($url, '?X-Amz'));

        $filePathLocal = public_path('timelines/tickets/' . $nameFile);

        if (file_exists($filePathLocal)) {
            File::delete($filePathLocal);
        }
    }

    public function pushOrderJsonToCloud($dataOrderJson, $nameFile)
    {
        $directory = public_path('data_json');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(public_path('data_json/' . $nameFile), $dataOrderJson);

        $sourceFilePath = public_path('data_json/' . $nameFile);

        $filePath = Storage::disk('b2')->putFileAs('/data_json', $sourceFilePath, $nameFile, 'public');

        $url = Storage::disk('b2')->temporaryUrl($filePath, '', []);

        $newUrl = substr($url, 0, strpos($url, '?X-Amz'));

        $filePathLocal = public_path('data_json/' . $nameFile);

        if (file_exists($filePathLocal)) {
            File::delete($filePathLocal);
        }
    }

    public function pushDataToCloud($dataOrderJson, $nameFile, $path)
    {
        $bucketId = $this->getBucketIdByName('pressifypod');
        $this->deleteAllVersionsByFileName($path . '/' . $nameFile, $bucketId);

        $fileContent = json_encode($dataOrderJson);
        $directory = public_path($path);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(public_path($path . '/' . $nameFile), $fileContent);

        $sourceFilePath = public_path($path . '/' . $nameFile);

        $filePath = Storage::disk('b2')->putFileAs('/' . $path, $sourceFilePath, $nameFile, 'public');

        $url = Storage::disk('b2')->temporaryUrl($filePath, '', []);

        $newUrl = substr($url, 0, strpos($url, '?X-Amz'));

        $filePathLocal = public_path($path . '/' . $nameFile);

        if (file_exists($filePathLocal)) {
            File::delete($filePathLocal);
        }
    }

    public function getTimelineTicket($dataTimeline, $pathFile)
    {
        $url = env('B2_URL') . '/timelines/tickets/';

        $response = Http::get($url . $pathFile);

        if ($response->failed()) {
            return $dataTimeline;
        } else {
            $response = json_decode($response->body(), true);

            return $response;
        }
    }
    public function getTimeline($dataTimeline, $pathFile)
    {
        $url = env('B2_URL_CLOUD');

        $response = Http::get($url . $pathFile);

        if ($response->failed()) {
            return $dataTimeline;
        } else {
            $response = json_decode($response->body(), true);

            return $response;
        }
    }
    public function getTimelineTickets($pathFile)
    {
        try {
            $url = '/timelines/tickets/';
            $response = Storage::disk('b2')->get($url . $pathFile);

            $response = json_decode($response, true);

            return $response;
        } catch (Exception $e) {
            return 0;
        }


    }
}
