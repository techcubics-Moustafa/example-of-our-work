<?php


namespace App\Helpers\CPU;


use App\Helpers\Setting\Utility;

class FirebaseNotification
{
    private $headers;
    private $title;
    private $body;
    private $image;
    private $data;
    private $tokens;

    private function headers(): array
    {
        $this->headers = [
            'Authorization: key=' . Utility::getValByName('FCM_SERVER_KEY'),
            'Content-Type: application/json',
            'accept: application/json'
        ];

        return $this->headers;
    }

    private function callApi($fields)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, Utility::getValByName('FCM_SERVER_URL'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);

        if (!$result) die('Curl failed: ' . curl_error($ch));

        curl_close($ch);

        return $result;
    }

    public function withTitle($title): static
    {
        $this->title = $title;

        return $this;
    }

    public function withBody($body): static
    {
        $this->body = $body;

        return $this;
    }

    public function withImage($image): static
    {
        $this->image = $image;

        return $this;
    }

    public function withData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function to(array $tokens): static
    {
        $this->tokens = $tokens;

        return $this;
    }

    public function asNotification(): bool|string|null
    {
        $data["click_action"] = "FLUTTER_NOTIFICATION_CLICK";
        $data = [
            "registration_ids" => $this->tokens,
            /* "notification" => [
                 'title' => $this->title,
                 'body' => $this->body
             ],*/
            "data" => [
                'title' => $this->title,
                'body' => $this->body,
                'info' => $this->data,
            ]
        ];

        $fields = json_encode($data);

        return (new self)->callApi($fields);
    }

}
