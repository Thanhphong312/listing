<?php

namespace Vanguard\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Vanguard\User;

class TelegramWebhookController extends Controller
{
    /**
     * Xử lý webhook khi nhận được cập nhật từ Telegram
     */
    public function handleWebhook(Request $request)
    {
        Log::channel('telegram-wh')->info('Telegram webhook data:', $request->all());
        try {
            // Nhận dữ liệu update
            $update = $request->all();

            $message = $update["message"] ?? null;
            if ($message) {
                $chatId = $message["chat"]["id"];
                $text = trim($message["text"]);
                // Log::info("Nhận tin nhắn từ chat ID: $chatId, Nội dung: $text");

                if (str_starts_with($text, '/login ')) {
                    return $this->handleLogin($chatId, $text);
                } elseif ($text === '/start' || $text === '/help') {
                    return $this->sendInstructions($chatId);
                } elseif ($text === '/startNotice') {
                    return $this->updateNotice($chatId, true);
                } elseif ($text === '/endNotice') {
                    return $this->updateNotice($chatId, false);
                } elseif ($text === '/logout') {
                    return $this->handleLogout($chatId);
                }

                return response()->json(['success' => true]);
            }

        } catch (\Throwable $th) {
            Log::channel('telegram-wh')->info($th);
        }
        
        // Trả về response cho Telegram biết đã xử lý xong
        return response()->json(['success' => true]);
    }

    /**
     * Thiết lập webhook với Telegram API
     */
    public function setWebhook()
    {
        $webhookUrl = env('TELEGRAM_WEBHOOK_URL');
        // Log::info('setWebhook');

        try {
            $response = Telegram::setWebhook(['url' => $webhookUrl]);

            return response()->json([
                'success' => true,
                'webhook_info' => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when setting webhook: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lấy thông tin webhook hiện tại
     */
    public function getWebhookInfo()
    {
        // Log::info('getWebhookInfo');

        try {
            $response = Telegram::getWebhookInfo();

            return response()->json([
                'success' => true,
                'webhook_info' => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when getting webhook info: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Xóa webhook hiện tại
     */
    public function removeWebhook()
    {
        // Log::info('removeWebhook');

        try {
            $response = Telegram::removeWebhook();

            return response()->json([
                'success' => true,
                'result' => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when removing webhook: ' . $e->getMessage(),
            ], 500);
        }
    }


    private function sendInstructions($chatId)
    {
        $text = "Danh sách lệnh bạn có thể sử dụng:\n";
        $text .= "/login username password - Đăng nhập\n";
        $text .= "/startNotice - Nhận thông báo\n";
        $text .= "/endNotice - Ngừng nhận thông báo\n";
        $text .= "/logout - Đăng xuất\n";
        $text .= "/help - Xem danh sách lệnh";
        Telegram::sendMessage(["chat_id" => $chatId, "text" => $text]);
    }

    private function handleLogin($chatId, $text)
    {
        $parts = explode(' ', $text);
        if (count($parts) !== 3) {
            return Telegram::sendMessage(["chat_id" => $chatId, "text" => "Sai cú pháp. Dùng: /login username password"]);
        }

        [$cmd, $username, $password] = $parts;
        $user = User::where('username', $username)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return Telegram::sendMessage(["chat_id" => $chatId, "text" => "Sai tài khoản hoặc mật khẩu"]);
        }

        $user->update(["group_id" => $chatId]);
        Telegram::sendMessage(["chat_id" => $chatId, "text" => "Đăng nhập thành công!"]);
    }

    private function updateNotice($chatId, $status)
    {
        $user = User::where('group_id', $chatId)->first();
        if (!$user) {
            return Telegram::sendMessage(["chat_id" => $chatId, "text" => "Bạn cần đăng nhập trước!"]);
        }

        $user->update(["send_notice" => $status]);
        $message = $status ? "Bắt đầu nhận thông báo." : "Ngưng nhận thông báo.";
        Telegram::sendMessage(["chat_id" => $chatId, "text" => $message]);
    }

    private function handleLogout($chatId)
    {
        $user = User::where('group_id', $chatId)->first();
        if (!$user) {
            return Telegram::sendMessage(["chat_id" => $chatId, "text" => "Bạn chưa đăng nhập!"]);
        }

        $user->update(["group_id" => null, "send_notice" => false]);
        Telegram::sendMessage(["chat_id" => $chatId, "text" => "Bạn đã đăng xuất."]);
    }
}
