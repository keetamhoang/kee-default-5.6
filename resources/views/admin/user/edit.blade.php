@extends('admin')

@section('content')
    <h3 class="h3-title">Chỉnh sửa bài viết</h3>

    @include('flash_message')

    <form action="{{ url('admin/posts/update') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" name="id" value="{{ $post->id }}">
        <div class="form-body">
            <div class="form-group">
                <label class="col-md-2 control-label">Tên bài viết</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Điền tên bài viết" name="name" value="{{ $post->name }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Danh mục</label>
                <div class="col-md-6">
                    @php $parentIds = \App\Model\Category::all() @endphp
                    <select class="form-control" name="category_id">
                        <option>--- Chọn danh mục ---</option>
                        @foreach($parentIds as $parentId)
                            <option value="{{ $parentId->id }}" @if ($parentId->id == $post->category_id) selected @endif>{{ $parentId->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Ảnh</label>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                            @if (!empty($post->image))
                                <img src="{{ $post->image }}" alt="" />
                            @else
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 500px;"> </div>
                        <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> Chọn ảnh </span>
                                                                    <span class="fileinput-exists"> Thay đổi </span>
                                                                    <input type="file" name="image"> </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Xóa </a>
                        </div>
                    </div>
                    <div class="clearfix margin-top-10"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Miêu tả ngắn</label>
                <div class="col-md-9">
                                <textarea class="form-control ckeditor" placeholder="Điền miêu tả ngắn"
                                          name="short_desc">{{ $post->short_desc }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Chi tiết</label>
                <div class="col-md-9">
                                <textarea class="form-control ckeditor" placeholder="Điền chi tiết"
                                          name="desc">{{ $post->desc }}</textarea>
                </div>
            </div>

            {{--<div class="form-group">--}}
                {{--<label class="col-md-2 control-label">Là dự án nổi bật?</label>--}}
                {{--<div class="col-md-9">--}}
                    {{--<div class="mt-checkbox-inline">--}}
                        {{--<label class="mt-checkbox mt-checkbox-outline">--}}
                            {{--<input type="checkbox" name="is_highlight" @if ($post->is_highlight == config('const.ACTIVE')) checked @endif>--}}
                            {{--<span></span>--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group">
                <label class="col-md-2 control-label">Có được hiển thị?</label>
                <div class="col-md-9">
                    <div class="mt-checkbox-inline">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="status" @if ($post->status == config('const.ACTIVE')) checked @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn green">Cập nhật</button>
                    <a type="button" class="btn default" href="{{ url('admin/posts') }}">Trở về</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#add-row').click(function (e) {
                var html = '<tr>' +
                    '<td class="col-md-6">' +
                    '<div class="form-group" style="margin: 0px;">' +
                    '<input type="text" class="form-control unicase-form-control text-input" name="categories[]">' +
                    '</div>' +
                    '</td>' +
                    '<td class="col-md-3">' +
                    '<div class="form-group" style="margin: 0px;">' +
                    '<input type="text" class="form-control unicase-form-control text-input" name="discounts[]">' +
                    '</div>' +
                    '</td>' +
                    '<td class="col-md-3">' +
                    '<div class="form-group" style="margin: 0px;">' +
                    '<input type="text" class="form-control unicase-form-control text-input" name="discounts_real[]">' +
                    '</div>' +
                    '</td>' +
                    '</tr>';

                $('.discount-category').append(html);
            });
        });
    </script>
@endsection