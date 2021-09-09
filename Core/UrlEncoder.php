<?php

namespace CryptoExchanges\Core;

class UrlEncoder 
{
    /**
     * Function to encode the url
     *
     * @param array $params
     * @return string
     */
    public function urlEncode(array $params = []) : string
    {
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $params[$key] = var_export($value, true);
            }
        }

        return http_build_query($params);
    }
}