<?php


class Bot
    {
        const string TOKEN = "7422856223:AAFzdJHamkQgtJg2lP6MOnsMrEWgE84Onb0";
        const string API   = "https://api.telegram.org/bot".self::TOKEN."/";
        
        public Client $http;
        

        public function __construct()
        {
            $this->http = new Client(['base_uri' => self::API]);
        }

        public function  handleStartCommand(int $chatId): void
        {
            $this->http->post('sendMessage',[
                'form_params' =>[
                    'chat_id' => $chatId,
                    'text' => 'Yana bitta Qonday!',
                ]
            ]);
        }

        public function  handleAddCommand(int $chatId): void
        {
            $this->http->post('sendMessage',[
                'form_params' =>[
                    'chat_id' => $chatId,
                    'text' => '!',
                ]
            ]);
        }
    }
    