<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

class CloudinaryHelper
{
    public static function upload($file)
    {
        $cloudinary = self::config();
        if (!$cloudinary) {
            return [
                'secure_url' => self::storeLocal($file),
                'public_id' => null
            ];
        }

        try {
            $apiKey = $cloudinary['api_key'];
            $apiSecret = $cloudinary['api_secret'];
            $cloudName = $cloudinary['cloud_name'];

            $timestamp = time();
            $signature = sha1('timestamp=' . $timestamp . $apiSecret);

            $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

            $post = [
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ];

            $curlFile = curl_file_create($file->getRealPath(), $file->getMimeType(), $file->getClientOriginalName());
            $post['file'] = $curlFile;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                Log::error('Cloudinary upload curl error: ' . $err);
                return [
                    'secure_url' => self::storeLocal($file),
                    'public_id' => null
                ];
            }

            $decoded = json_decode($result, true);
            if (isset($decoded['secure_url'])) {
                return [
                    'secure_url' => $decoded['secure_url'],
                    'public_id' => $decoded['public_id'] ?? null
                ];
            }

            Log::error('Cloudinary upload failed: ' . $result);
            return [
                'secure_url' => self::storeLocal($file),
                'public_id' => null
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload exception: ' . $e->getMessage());
            return [
                'secure_url' => self::storeLocal($file),
                'public_id' => null
            ];
        }
    }

    public static function delete($publicId)
    {
        if (!$publicId) {
            return false;
        }

        $cloudinary = self::config();
        if (!$cloudinary) {
            return false;
        }

        try {
            $apiKey = $cloudinary['api_key'];
            $apiSecret = $cloudinary['api_secret'];
            $cloudName = $cloudinary['cloud_name'];

            $url = "https://api.cloudinary.com/v1_1/{$cloudName}/resources/image/upload";
            $query = http_build_query(['public_ids' => [$publicId]]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':' . $apiSecret);
            $result = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                Log::error('Cloudinary delete curl error: ' . $err);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function deleteProductImage($producto)
    {
        if ($producto->imagen_public_id) {
            self::delete($producto->imagen_public_id);
            return;
        }

        if (!$producto->imagen_url) {
            return;
        }

        $path = parse_url($producto->imagen_url, PHP_URL_PATH);
        if (!$path) {
            return;
        }

        if (str_starts_with($path, '/img/productos/')) {
            $fullPath = public_path(ltrim($path, '/'));
            if (is_file($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    protected static function storeLocal($file): string
    {
        $directory = public_path('img/productos');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = uniqid('producto_', true) . '_' . $file->getClientOriginalName();
        $file->move($directory, $filename);

        return '/img/productos/' . $filename;
    }

    protected static function config(): ?array
    {
        $url = getenv('CLOUDINARY_URL') ?: (isset($_ENV['CLOUDINARY_URL']) ? $_ENV['CLOUDINARY_URL'] : env('CLOUDINARY_URL'));

        if (!is_string($url) || $url === '') {
            return null;
        }

        $pattern = '/^cloudinary:\/\/([^:]+):([^@]+)@([^\/]+)$/';
        if (!preg_match($pattern, $url, $matches)) {
            return null;
        }

        return [
            'api_key' => rawurldecode($matches[1]),
            'api_secret' => rawurldecode($matches[2]),
            'cloud_name' => rawurldecode($matches[3]),
        ];
    }
}
