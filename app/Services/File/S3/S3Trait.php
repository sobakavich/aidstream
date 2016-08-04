<?php namespace App\Services\File\S3;

trait S3Trait
{
    protected function getUrl($bucket, $key)
    {
        $request   = $this->createPresignedRequest($bucket, $key);
        $host      = $request->getUri()->getScheme() . '://' . $request->getUri()->gethost();
        $path      = $request->getUri()->getPath();
        $this->url = $host . $path;

        return $this->url;
    }

    protected function getBucket()
    {
        return config('filesystems.disks.s3.bucket');
    }

    protected function getDisk()
    {
        return $this->filesystemManager->disk('s3');
    }

    protected function getS3Client()
    {
        return $this->disk->getDriver()->getAdapter()->getClient();
    }

    protected function getCommand($bucket, $key)
    {
        return $this->client->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $key]);
    }

    protected function createPresignedRequest($bucket, $key)
    {
        $command = $this->getCommand($bucket, $key);

        return $this->client->createPresignedRequest($command, '+20 minutes');
    }
}