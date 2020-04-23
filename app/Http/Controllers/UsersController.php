<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
class UsersController extends Controller
{
        //构造方法限制未登录修改
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }
    //个人页面的展示：
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    //编辑个人信息
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}
