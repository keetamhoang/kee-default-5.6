<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UserController extends AdminController
{
    public function index() {
        return view('admin.user.index');
    }

    public function userAttribute(Request $request) {
        $brands = User::orderBy('id', 'asc')->get();

        return $this->datatable($brands);
    }

    public function datatable($brands)
    {
        return DataTables::of($brands)
            ->editColumn('role', function ($brand) {
                $html = 'UNKNOWN';

                if ($brand->role == 'admin') {
                    $html = '<label class="label label-success">ADMIN</label>';
                } elseif ($brand->role == 'mod') {
                    $html = '<label class="label label-primary">MOD</label>';
                } elseif ($brand->role == 'staff') {
                    $html = '<label class="label label-danger">STAFF</label>';
                }

                return $html;
            })
            ->rawColumns(['role'])
            ->make(true);
    }

    public function create() {
        return view('admin.user.create');
    }

    public function store(Request $request) {
        $data = $request->all();

        $data['image'] = ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';

        $data['status'] = !empty($data['status']) and $data['status'] == 'on' ? 1: 0;
//        $data['is_highlight'] = !empty($data['is_highlight']) and $data['is_highlight'] == 'on' ? 1: 0;

        $data['slug'] = Functions::convertSlug($data['name']);

        $data['created_by'] = auth('admin')->user()->id;

        try {
            $post = Post::create($data);
        } catch (\Exception $e) {
            return redirect('admin/posts/add')->with('error','Lỗi! Thêm mới không thành công');
        }

        return redirect('admin/posts/'. $post->id)->with('success', 'Thêm mới thành công');
    }

    public function edit($id) {
        $post = Post::find($id);

        if (empty($post)) {
            return redirect()->back()->with('error', 'Không tồn tại');
        }

        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request) {
        $data = $request->all();

        $post = Post::find($data['id']);

        if (empty($post)) {
            return redirect()->back()->with('error', 'Không tồn tại');
        }

        $data['image'] = ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';

        if (empty($data['image'])) {
            unset($data['image']);
        }

        $data['slug'] = Functions::convertSlug($data['name']);

        $data['status'] = !empty($data['status']) and $data['status'] == 'on' ? 1: 0;
        $data['is_highlight'] = !empty($data['is_highlight']) and $data['is_highlight'] == 'on' ? 1: 0;

        $post->update($data);

        return redirect('admin/posts/'. $post->id)->with('success', 'Cập nhật thành công');
    }

    public function delete($id) {
        $post = Post::find($id);

        if (empty($post)) {
            return redirect('admin/posts')->with('error', 'Xóa không thành công');
        }

        $post->delete();

        return redirect('admin/posts')->with('success', 'Xóa thành công');
    }
}
