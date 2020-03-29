<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data = null, $msg = 'success', $code = 20000)
    {
        if (is_null($data)) {
            return response()->json(['code' => $code, 'msg' => $msg, 'data' => new \stdClass()]);
        } else {
            return response()->json(['code' => $code, 'msg' => $msg, 'data' => $this->deleteNull($data)]);
        }
    }

    protected function error($msg = 'error', $code = 40000)
    {
        return response()->json(['code' => $code, 'msg' => $msg]);
    }

    private function deleteNull($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                unset($data[$key]);
                $data[Str::camel($key)] = $this->deleteNull($val);
            }
            return $data;
        } else {
            if (is_null($data)) {
                return '';
            } else {
                return $data;
            }
        }
    }

    public function SafeFilter(&$arr)
    {

        $ra = Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '/script/', '/javascript/', '/vbscript/', '/expression/', '/applet/', '/meta/', '/xml/', '/blink/', '/link/', '/style/', '/embed/', '/object/', '/frame/', '/layer/', '/title/', '/bgsound/', '/base/', '/onload/', '/onunload/', '/onchange/', '/onsubmit/', '/onreset/', '/onselect/', '/onblur/', '/onfocus/', '/onabort/', '/onkeydown/', '/onkeypress/', '/onkeyup/', '/onclick/', '/ondblclick/', '/onmousedown/', '/onmousemove/', '/onmouseout/', '/onmouseover/', '/onmouseup/', '/onunload/');

        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                if (!is_array($value)) {
                    if (!get_magic_quotes_gpc())//不对magic_quotes_gpc转义过的字符使用addslashes(),避免双重转义。
                    {
                        $value = addslashes($value); //给单引号（'）、双引号（"）、反斜线（\）与NUL（NULL字符）加上反斜线转义
                    }
                    $value = preg_replace($ra, '', $value);     //删除非打印字符，粗暴式过滤xss可疑字符串
                    $arr[$key] = htmlentities(strip_tags($value)); //去除 HTML 和 PHP 标记并转换为HTML实体
                } else {
                    $this->SafeFilter($arr[$key]);
                }
            }
        }
    }

}
