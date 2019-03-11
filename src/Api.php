<?php

namespace Justify\TelegramBotApi;

use Justify\TelegramBotApi\Types\ReplyMarkup;
use Justify\TelegramBotApi\Types\InputMedia;

/**
 * Telegram bot API requests wrapper
 *
 * @link https://core.telegram.org/bots/api
 */
class Api
{
    /**
     * Telegram Bot API URL
     *
     * 1st %s - token
     * 2nd %s - method
     */
    const URL = 'https://api.telegram.org/bot%s/%s?';

    /**
     * Maximum possible count of group items
     */
    const MAX_MEDIA_GROUP_ITEMS = 10;

    /**
     * @var string Telegram bot API token
     */
    private $token;

    /**
     * @var boolean if true, information will present in array else in Std object
     */
    private $responseArray = false;

    /**
     * Class constructor
     *
     * @param string $token Telegram bot API token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Receives incoming updates using long polling
     *
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#getupdates
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function getUpdates(array $optional = [])
    {
        return $this->sendRequest('getUpdates', $optional);
    }

    /**
     * A simple method for testing your bot's auth token
     *
     * @link https://core.telegram.org/bots/api#getme
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function getMe()
    {
        return $this->sendRequest('getMe');
    }

    /**
     * Method sends text messages
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $text text of the message to be sent
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendMessage($chatId, $text, array $optional = [])
    {
        $params = array_merge(['chat_id' => $chatId, 'text' => $text], $optional);

        return $this->sendRequest('sendMessage', $params);
    }

    /**
     * Forwards messages of any kind
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param integer $fromChatId identifier for the chat where the original message was sent
     * @param integer $messageId message identifier in the chat specified in from_chat_id
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#forwardmessage
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function forwardMessage($chatId, $fromChatId, $messageId, array $optional = [])
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'from_chat_id' => $fromChatId,
            'message_id' => $messageId
        ], $optional);

        return $this->sendRequest('forwardMessage', $params);
    }

    /**
     * Sends photo
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $photo photo to send. Pass string with id of photo or photo URL or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendphoto
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendPhoto($chatId, $photo, array $optional = [])
    {
        if (!file_exists($photo)) {
            $params = array_merge(['chat_id' => $chatId, 'photo' => $photo], $optional);

            return $this->sendRequest('sendPhoto', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendPhoto', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'photo' => new \CurlFile($photo)]
        ]);
    }

    /**
     * Sends audio. Accepts .mp3 format up to 50 mb in size
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $audio audio to send. Pass string with id of audio or audio URL or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendaudio
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendAudio($chatId, $audio, array $optional = [])
    {
        if (!file_exists($audio)) {
            $params = array_merge(['chat_id' => $chatId, 'audio' => $audio], $optional);

            return $this->sendRequest('sendAudio', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendAudio', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'audio' => new \CurlFile($audio)]
        ]);
    }

    /**
     * Sends general files. Accepts any type up to 50 mb in size
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $document document to send. Pass string with id of document or document URL or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#senddocument
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendDocument($chatId, $document, array $optional = [])
    {
        if (!file_exists($document)) {
            $params = array_merge(['chat_id' => $chatId, 'document' => $document], $optional);

            return $this->sendRequest('sendDocument', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendDocument', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'document' => new \CurlFile($document)]
        ]);
    }

    /**
     * Sends video file. Accepts .mp4 format up to 50 mb in size
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $video video to send. Pass string with id of video or video URL or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendvideo
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendVideo($chatId, $video, array $optional = [])
    {
        if (!file_exists($video)) {
            $params = array_merge(['chat_id' => $chatId, 'video' => $video], $optional);

            return $this->sendRequest('sendVideo', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendVideo', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'video' => new \CurlFile($video)]
        ]);
    }

    /**
     * Sends animation files (GIF or H.264/MPEG-4 AVC video without sound). Accepts up to 50 mb in size
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $animation animation to send. Pass string with id of animation or animation URL or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendanimation
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendAnimation($chatId, $animation, array $optional = [])
    {
        if (!file_exists($animation)) {
            $params = array_merge(['chat_id' => $chatId, 'animation' => $animation], $optional);

            return $this->sendRequest('sendAnimation', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendAnimation', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'animation' => new \CurlFile($animation)]
        ]);
    }

    /**
     * Sends voice. Accepts .ogg format encoded with OPUS up to 50 mb in size
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $voice voice to send. Pass string with id of voice or voice URL or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendvoice
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendVoice($chatId, $voice, array $optional = [])
    {
        if (!file_exists($voice)) {
            $params = array_merge(['chat_id' => $chatId, 'voice' => $voice], $optional);

            return $this->sendRequest('sendVoice', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendVoice', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'voice' => new \CurlFile($voice)]
        ]);
    }

    /**
     * Send a group of photos or videos as an album
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $videoNote video note to send. Pass string with id of voice or voice URL or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendvideonote
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendVideoNote($chatId, $videoNote, array $optional = [])
    {
        if (!file_exists($videoNote)) {
            $params = array_merge(['chat_id' => $chatId, 'video_note' => $videoNote], $optional);

            return $this->sendRequest('sendVideoNote', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendVideoNote', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'video_note' => new \CurlFile($videoNote)]
        ]);
    }

    /**
     * Send a group of photos or videos as an album
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param array $media array describing photos and videos to be sent, must include 2–10 items
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendmediagroup
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendMediaGroup($chatId, array $media, array $optional = [])
    {
        if (count($media) > self::MAX_MEDIA_GROUP_ITEMS) {
            throw new Exception('Too much messages to send as an album, need ' . self::MAX_MEDIA_GROUP_ITEMS);
        }

        $params = $optional;

        foreach ($media as $item) {
            if ($item instanceof InputMedia) {
                $params['media'][] = $item->getParams();
            } else {
                $params['media'][] = $item;
            }
        }

        $params['chat_id'] = $chatId;
        $params['media'] = json_encode($params['media']);

        return $this->sendRequest('sendMediaGroup', $params);
    }

    /**
     * Sends point on the map
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param float $latitude latitude of the location
     * @param float $longitude longitude of the location
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendlocation
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendLocation($chatId, $latitude, $longitude, array $optional = [])
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude
        ], $optional);

        return $this->sendRequest('sendLocation', $params);
    }

    /**
     * Sends sticker. Accepts .webp format
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $sticker sticker to send. Pass string with id of video or pass file name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendsticker
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendSticker($chatId, $sticker, array $optional = [])
    {
        if (!file_exists($sticker)) {
            $params = array_merge(['chat_id' => $chatId, 'sticker' => $sticker], $optional);

            return $this->sendRequest('sendSticker', $params);
        }

        $params = array_merge(['chat_id' => $chatId], $optional);

        return $this->sendRequest('sendSticker', $params, [
            CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
            CURLOPT_POSTFIELDS => ['chat_id' => $chatId, 'sticker' => new \CurlFile($sticker)]
        ]);
    }

    /**
     * Sends information about a venue
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param float $latitude latitude of the location
     * @param float $longitude longitude of the location
     * @param string $title name of the venue
     * @param string $address address of the venue
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendvenue
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendVenue($chatId, $latitude, $longitude, $title, $address, array $optional = [])
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'title' => $title,
            'address' => $address
        ], $optional);

        return $this->sendRequest('sendVenue', $params);
    }

    /**
     * Sends phone contact
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $phoneNumber contact's phone number
     * @param string $firstName contact's first name
     * @param array $optional optional params
     *
     * @link https://core.telegram.org/bots/api#sendcontact
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendContact($chatId, $phoneNumber, $firstName, array $optional = [])
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'phone_number' => $phoneNumber,
            'first_name' => $firstName
        ], $optional);

        return $this->sendRequest('sendContact', $params);
    }

    /**
     * Use this method when you need to tell the user that something is happening on the bot's side
     * The status is set for 5 seconds or less
     *
     * @param integer|string $chatId unique identifier for the target chat or username of the target channel
     * @param string $action type of action to broadcast
     *
     * @link https://core.telegram.org/bots/api#sendchataction
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendChatAction($chatId, $action)
    {
        return $this->sendRequest('sendChatAction', ['chat_id' => $chatId, 'action' => $action]);
    }

    /**
     * Use this method to get basic info about a file and prepare it for downloading
     *
     * @param integer $fileId file identifier to get info about
     *
     * @link https://core.telegram.org/bots/api#getfile
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function getFile($fileId)
    {
        return $this->sendRequest('getFile', ['file_id' => $fileId]);
    }

    /**
     * Clears updates from getUpdates method
     *
     * @throws \Justify\TelegramBotApi\Exception
     */
    public function clearUpdates()
    {
        $updates = $this->getUpdates()->result;

        if (!empty($updates)) {
            $lastUpdateId = array_pop($updates)->update_id;

            $this->getUpdates(['offset' => $lastUpdateId + 1]);
        }
    }

    /**
     * Sends custom request
     *
     * @var string $method calls method
     * @var array $params optional, request params
     * @var array $curlParams optional, CURL option params
     *
     * @link https://core.telegram.org/bots/api#making-requests
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return object|array response from sent request
     */
    public function sendRequest($method, array $params = [], array $culrParams = [])
    {
        if (isset($params['reply_markup']) && $params['reply_markup'] instanceof ReplyMarkup) {
            $params['reply_markup'] = $params['reply_markup']->getParams();
        }

        $ch = curl_init($this->getUrl($method, $params));

        if (empty($culrParams)) {
            $culrParams = [
                CURLOPT_HEADER => false,
                CURLOPT_NOBODY => false
            ];
        }

        $culrParams[CURLOPT_RETURNTRANSFER] = true;

        curl_setopt_array($ch, $culrParams);

        $response = json_decode(curl_exec($ch), $this->responseArray);

        if (!$response) {
            throw new Exception('Probably no internet connection');
        }

        if (!$response->ok) {
            throw new Exception("Error {$response->error_code}: {$response->description}");
        }

        return $response;
    }

    /**
     * Use this method if you want receive information in array
     */
    public function setResponseArray()
    {
        $this->responseArray = true;
    }

    /**
     * Use this method if you want receive information in Std object
     */
    public function setResponseObject()
    {
        $this->responseArray = false;
    }

    /**
     * URL to which need to send a request
     *
     * @param string $method method name
     * @param array $params request params
     * @return string
     */
    private function getUrl($method, array $params = [])
    {
        return sprintf(self::URL, $this->token, $method) . http_build_query($params);
    }
}
