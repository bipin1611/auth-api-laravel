<?php
namespace App\Helper;


/**
*
* JSON Response
 *
*/

class ResponseMessage {

    /**
     * JSON Response for success
     *
     *  @author Bipin Parmar
     *
     */
	public static function success($msg,$data)
	{

        return response()->json(['status' => 200, 'error' => false, 'message' => $msg, 'data' => $data]);

	}


	/**
     * JSON Response for success
     *
     *  @author Bipin Parmar
     *
     */
	public static function successWithToken($msg,$data, $token)
	{
        return response()->json(['status' => 200, 'error' => false, 'message' => $msg, 'data' => $data, 'Authorization'=> $token]);
	}

    /**
     * JSON Response for error
     *
     *  @author Bipin Parmar
     *
     */
	public static function error($msg)
	{
        return response()->json(['errors' => $msg],422);
	}

}
