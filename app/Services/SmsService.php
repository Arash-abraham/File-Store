<?php
namespace App\Services;

use Kavenegar\KavenegarApi;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $api;
    protected $sender;

    public function __construct()
    {
        $this->api = new KavenegarApi(env('KAVENEGAR_API_KEY'));
        $this->sender = env('KAVENEGAR_SENDER');
    }

    public function sendSms($receptor, $message)
    {
        try {
            $this->api->Send($this->sender, $receptor, $message);
            return ['status' => 'success', 'message' => 'پیامک با موفقیت ارسال شد'];
        } catch (ApiException $e) {
            Log::error('Kavenegar API Error: ' . $e->getMessage());
            throw new \Exception('خطا در API کاوه‌نگار: ' . $e->getMessage());
        } catch (HttpException $e) {
            Log::error('Kavenegar HTTP Error: ' . $e->getMessage());
            throw new \Exception('خطا در اتصال به کاوه‌نگار: ' . $e->getMessage());
        }
    }
}