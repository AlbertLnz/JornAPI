<?php
    declare(strict_types=1);

namespace App\Services\Redis;


    use Predis\Client;
    use App\DTOs\SomeDataDTO;
use Predis\Response\Status;

    class RedisService
    {
        protected $redis;

        public function __construct()
        {
            // Configuración de conexión a Upstash usando pRedis
            $this->redis = new Client([
                'scheme' => 'tls',
                'host'   => ENV('REDIS_CUSTOM_HOST'),
                'port'   => ENV('REDIS_CUSTOM_PORT'),
                'password' => ENV('REDIS_CUSTOM_PASSWORD'),
            ]);
        }

        public function get( string $key)
        {
            $data = $this->redis->get($key);
            return $data ? unserialize($data) : null;  
        }


       
        public function set($key, $value): Status|null
        {
            $serializedValue = serialize($value);
            return $this->redis->set($key, $serializedValue);
        }

       
        public function listPush($listKey, $value)
        {
            return $this->redis->rpush($listKey, [serialize($value)]); 
        }

     
        public function listGet($listKey)
        {
            $items = $this->redis->lrange($listKey, 0, -1);
            return array_map(function ($item) {
                return unserialize($item);  // Deserializar los DTOs de la lista
            }, $items);
        }

        public function delete($key)
        {
            return $this->redis->del($key);
        }
    }
