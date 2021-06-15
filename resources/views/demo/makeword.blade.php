@extends('demo/layouts/index')
@section('title')
    Create Word
@parent
@stop
@section('header_styles')
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link rel="stylesheet" href="{{asset('css/makeword.css')}}">
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css"> -->
@stop
@section('content')
<div class="form-login">
    <h4>Form thêm từ!</h4>
    <form method="post" action="{{route('user.createWord')}}" id="form-create">
        <div class="container-fuild">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="inputHre">
                        Tiếng hre
                        <sup><i class="fas fa-star-of-life text-danger"></i></sup>
                    </label>
                    <input type="text" class="form-control" id="inputHre" placeholder="Tiếng hre" name="hre" autocomplete="off">
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-group">
                    <label for="inputVN">
                        Nghĩa tiếng việt
                        <sup><i class="fas fa-star-of-life text-danger"></i></sup>
                    </label>
                    <input type="text" class="form-control" id="inputVN" placeholder="Nghĩa tiếng việt" required="" name="vnMain" autocomplete="off">
                </div>
                <div class="col-md-3 form-group">
                    <label for="selectVN">
                        Loại từ
                        <sup><i class="fas fa-star-of-life text-danger"></i></sup>
                    </label>
                    <select class="form-control" id="selectVN" name="typeMain">
                        @foreach($wordtype as $word)
                            <option value="{{$word->id}}">{{$word->plaintext}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-group">
                    <label for="inputVN2">Nghĩa tiếng việt 2(nếu có)</label>
                    <input type="text" class="form-control" id="inputVN2" placeholder="Nghĩa tiếng việt 2(nếu có)" name="vn2">
                </div>
                <div class="col-md-3 form-group">
                    <label for="selectVN2">Loại từ</label>
                    <select class="form-control" id="selectVN2" name="type2">
                        @foreach($wordtype as $word)
                            <option value="{{$word->id}}">{{$word->plaintext}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-group">
                    <label for="inputVN3">Nghĩa tiếng việt 3(nếu có)</label>
                    <input type="text" class="form-control" id="inputVN3" placeholder="Nghĩa tiếng việt 3(nếu có)" name="vn3">
                </div>
                <div class="col-md-3 form-group">
                    <label for="selectVN3">Loại từ</label>
                    <select class="form-control" id="selectVN3" name="type3">
                        @foreach($wordtype as $word)
                            <option value="{{$word->id}}">{{$word->plaintext}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Thêm từ</button>
    </form>
</div>


<div class="block-table">
    <h4>Danh sách các từ bạn đã thêm!</h4>
    <table class="table table-bordered table-striped" id="Table_Word">
        <thead>
            <tr>
                <th>STT</th>
                <th>Hre</th>
                <th>Tiếng việt</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- modal delete-->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Bạn có muốn?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="font-weight-bold "> Bạn có muốn xóa chữ <span class="text-danger font-italic" id="text-content"></span> không?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-id="" id="btn-delete">Xóa</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
            </div>
        </div>
    </div>
</div>
<!-- /modal delete -->
@stop
@section('footer-js')
<script type="text/javascript" src="{{asset('datatables/js/jquery.dataTables.js')}}" ></script>
<script type="text/javascript" src="{{asset('datatables/js/dataTables.bootstrap4.js')}}" ></script>
<script type="text/javascript">
    $('#modalDelete').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let id = button.data('id');
        let text = button.data('text');

        $("#text-content").text(text);
        $("#btn-delete").attr("data-id",id);

    })
    $("#btn-delete").click(function() {
        let id = $(this).data("id");
        $.ajax({
            method: "post",
            url: "{{route('user.deleteWord')}}",
            data: {id: id},
            success: function (data) {
                alert(data);
                table.ajax.url('{!! route("user.loadWord") !!}').load();
            }
        });
    })
    var table = $('#Table_Word').DataTable({
        "language": {
            "emptyTable": "Không có bản ghi phù hợp",
            "sLengthMenu": "Hiển thị _MENU_ bản ghi",
            "search": "Tìm kiếm:",
            "info": "Đang hiển thị trang _START_ của _END_ trong số _TOTAL_ mục",
            "paginate": {
                "previous": "Trang trước",
                "next": "Trang sau"
            }
        },
        "lengthMenu": [[5, 10, 50 , -1], [5, 10, 50,"All"]],
        "columnDefs": [
            { className: "id_action", "targets": [ 3 ] },
        ],
        "order": [[ 1, 'asc' ]],
        processing: true,
        ajax: '{!! route('user.loadWord') !!}',
        order:[],
        columns: [
            { data: 'stt', name: 'stt' },
            { data: 'plaintext', name: 'plaintext' },
            { data: 'means', name: 'means' },
            { data: 'actions', name: 'actions' },
        ]
    });
    table.on( 'draw.dt', function () {
        var PageInfo = $('#Table_Word').DataTable().page.info();
        table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    });

    $(document).ready(function() {
        $("#form-create").on("submit",function(e) {
            e.preventDefault();
            if($("#inputHre").val() == "" || $("#inputVN").val() == "")
                alert("Bạn nhập thiếu trường bắt buộc");
            else
            {
                let method = $(this).attr("method");
                let action = $(this).attr("action");
                
                $.ajax({
                    method: method,
                    url: action,
                    data: $(this).serialize(),
                    success: function (data) {
                        alert(data);
                        $("#inputHre").val("");
                        $("#inputVN").val("");
                        $("#inputVN2").val("");
                        $("#inputVN3").val("");
                        table.ajax.url('{!! route("user.loadWord") !!}').load();
                    }
                });
            }
        })
    })
</script>
@stop