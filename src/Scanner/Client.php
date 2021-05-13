<?php

namespace TikTok\Scanner;

class Client
{
    /**
     * @var string
     */
    private $_ip;
    
    /**
     * @var int
     */
    private $_port;
    
    /**
     * @param string $ip
     * @param int $port
     */
    public function __construct(string $ip, int $port)
    {
        $this->_ip = $ip;
        $this->_port = $port;
    }
    
    /**
     * @param string $profileName
     * @return array
     */
    public function requestProfileInfo(string $profileName)
    {
        return $this->executeRequest('/profile/' . $profileName);
    }
    
    /**
     * @param string $profileName
     * @param int    $offset
     * @return array
     */
    public function requestPosts(string $profileName, int $offset = 0)
    {
        return $this->executeRequest('/postslist/' . $profileName . '/' . $offset);
    }
    
    /**
     * @param string $requestUri
     * @return array|false
     */
    protected function executeRequest(string $requestUri)
    {
        $ch = curl_init('http://' . $this->_ip . ':' . $this->_port . $requestUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Connection: close',
        ));
        
        $response = curl_exec($ch);
        
        if (!curl_errno($ch)) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $header_size);
            curl_close($ch);
            return empty($body) ? [] : json_decode($body, true);
        }
        
        curl_close($ch);
        return false;
    }
}