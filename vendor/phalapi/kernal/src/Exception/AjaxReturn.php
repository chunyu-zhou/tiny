<?php
namespace PhalApi\Exception;

use PhalApi\Exception;
/**
 * Created by One admin@zcyso.com.
 * User: 周春宇
 * Date: 2017/12/29
 * Time: 下午10:48
 */

class AjaxReturn extends Exception {

    public function __construct($data = [], $message, $code = 200,$num = 0) {
        parent::__construct(
            \PhalApi\T($message, array('message' => $message)), $code + $num
        );
    }
}
