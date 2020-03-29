<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->has('file')) {
            return $this->error('文件不可以为空');
        }
        $file = $request->file('file');
        // 文件是否上传成功
        if ($file->isValid()) {

            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            if (!in_array($ext, array("gif", "png", "jpg", "jpeg", "bmp"))) {
                return $this->error('请上传正确的图片！');
            }
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg


            // 上传文件
            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            $bool = Storage::disk('public')->put($filename, file_get_contents($realPath));
            if ($bool) {
                return $this->success(['imgUrl' => asset('/storage/' . $filename), 'imgPath' => '/storage/' . $filename]);
            }
        }
        return $this->error();
    }
}
