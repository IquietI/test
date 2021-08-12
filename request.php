<?php
/**
 * Created by PhpStorm.
 * User: LuCiF3R
 * Date: 8/7/2021
 * Time: 1:52 PM
 */
class SimpleJsonRequest
{

    private static function makeRequest(string $method, string $url, array $parameters = null, array $data = null)
    {
        $opts = [
            'http' => [
                'method'  => $method,
                'header'  => 'Content-type: application/json',
                'content' => $data ? json_encode($data) : null
            ]
        ];

        $url .= ($parameters ? '?' . http_build_query($parameters) : '');
        return file_get_contents($url, false, stream_context_create($opts));
    }

    public static function get(string $url, array $parameters = null)
    {
        return json_decode(self::makeRequest('GET', $url, $parameters));
    }

    public static function post(string $url, array $parameters = null, array $data)
    {
        return json_decode(self::makeRequest('POST', $url, $parameters, $data));
    }

    public static function put(string $url, array $parameters = null, array $data)
    {
        return json_decode(self::makeRequest('PUT', $url, $parameters, $data));
    }

    public static function patch(string $url, array $parameters = null, array $data)
    {
        return json_decode(self::makeRequest('PATCH', $url, $parameters, $data));
    }

    public static function delete(string $url, array $parameters = null, array $data = null)
    {
        return json_decode(self::makeRequest('DELETE', $url, $parameters, $data));
    }



    function openRedisConnection( $hostName, $port){
        global $redisObj;
        // Opening a redis connection
        $redisObj->connect( $hostName, $port );
        return $redisObj;
    }

    function setValueWithTtl( $key, $value, $ttl ){

        try{
            global $redisObj;
            // setting the value in redis
            $redisObj->setex( $key, $ttl, $value );
        }catch( Exception $e ){
            echo $e->getMessage();
        }
    }
    function getValueFromKey( $key ){
        try{
            global $redisObj;
            // getting the value from redis
            return $redisObj->get( $key);
        }catch( Exception $e ){
            echo $e->getMessage();
        }
    }
    function deleteValueFromKey( $key ){
        try{
            global $redisObj;
            // deleting the value from redis
            $redisObj->del( $key);
        }catch( Exception $e ){
            echo $e->getMessage();
        }
    }

    /* Functions for converting sql result  object to array goes below  */

    function convertToArray( $result ){
        $resultArray = array();

        for( $count=0; $row = $result->fetch_assoc(); $count++ ) {
            $resultArray[$count] = $row;
        }

        return $resultArray;
    }

    /* Functions for executing the mySql query goes below   */

    function executeQuery( $query ){
        $mysqli = new mysqli( 'localhost',  'username',  'password',  'someDatabase' );

        if( $mysqli->connect_errno ){
            echo "Failed to connect to MySql:"."(".mysqli_connect_error().")".mysqli_connect_errno();
        }

        $result =  $mysqli->query( $query );
        // Calling function to convert result  to array
        $arrResult = convertToArray( $result );

        return $arrResult;
    }
}