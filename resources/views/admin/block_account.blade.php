@extends('layouts.default')
@section('styles')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title2')
	<h2>Dashboard</h2><p></p>
@endsection
@section('main')
<input type="hidden" value="{{$user_uuid}}" id="js_admin_account_id">
<div class="dashboard__wrapper-main bg-lightgray">
	@if (session("success"))
            <div class="alert alert-success alert-dismissible fade show success_alert" role="alert" id="js_alert_success">
            {{session("success")}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>     
	@endif
	<div class="dashboard__wrapper-table-area">
		<div class="dashboard__wrapper-table-box p-20">
			<div class="dashboard__wrapper-table-ttl">
				<p>ブロックユーザー一覧</p>
			</div>  
		</div>
		<div class="dashboard__wrapper-table">
			<table class="table table-striped">
				<thead>
				<tr>
					<th scope="col"></th>
					<th scope="col">ユーザー名</th>
					<th scope="col">最新受信日</th>
					<th scope="col">作成日時</th>
					<th scope="col">管理</th>
				</tr>
				</thead>
				<tbody class="js_table">
				@foreach ($block_lists as $chat_user)
					<tr data-id={{$chat_user["uuid"]}} class="js_chatUser_id">
					<th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
					<td w20><?= $chat_user["line_name"]?></td>
					<td class="js_latest_message_date">{{$chat_user["latest_message_date"] ? $chat_user["latest_message_date"]->format('Y-m-d H:i') : ""}}</td>
					<td><?= $chat_user["created_at"]->format('Y-m-d H:i') ?></td>
					<td class="operation">
						<button class="operation_icon js_block_btn" data-uuid={{$chat_user["uuid"]}} data-name={{$chat_user["line_name"]}} data-id={{$chat_user["id"]}}><img src="{{asset("img/icons8-no-entry-24.png")}}" alt=""></button>
					</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

{{-- 編集モーダル --}}
<section class="modal__container js_modal hidden" id="js_edit_account_modal">
	<h3 class="modal__container-ttl">編集</h3>
	@if ($errors->any())
	<div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
		<ul>
			@foreach ($errors->all() as $error)
			<li class="alert_danger">{{$error}}</li>
			@endforeach  
		</ul>   
	</div>  
	@endif
	<form action="{{route("user.update", ["id"=> ":id"])}}" method="post" class="js_edit_account_form">
		@csrf
		<div class="mb-3">
			<label for="formGroupExampleInput" class="form-label ">ユーザー名 <span style="color: red; font-size: 13px;">※</span></label>
			<input type="text" class="form-control js_edit_account_input" id="formGroupExampleInput" name="account_name" value="{{old("account_name")}}">
		</div>

		<input type="hidden" name="account_id" value="{{old("account_id")}}" class="js_account_id_input">
		<button type="submit" class="modal__container-btn">更新</button>
	</form>
</section>
{{-- ユーザーブロック確認モーダル --}}
<section class="modal__container js_modal hidden" id="js_block_account_modal" style="width: 500px;">
	<h2 class="modal__container-ttl" style="color: red; font-weight: bold;">本当にこのユーザーをブロックしますか？</h2>
	<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi aliquid beatae veritatis iusto consequatur fugiat at perspiciatis deserunt reprehenderit, molestiae nam voluptas praesentium cum error repudiandae, aspernatur repellat nulla iste.</p>
	<div style="margin-top: 15px"></div>
	<p>ユーザー名：<span id="js_account_name"></span></p>
	<div class="delete_account-btn-container">
		<div class="cancel-btn btn-box js_delete_account_cancel">
			<button class="delete_account-btn" readonly>キャンセル</button>
		</div>
		<form action="{{route('account.unblock', ['id' => 'ID_PLACEHOLDER'])}}" method="get" class="btn-box delete-btn js_block_account_from">
			@csrf
			<button class="delete_account-btn" type="submit" style="color: #fff;" readonly>ブロック</button>
		</form>
</div>
</section>

@endsection

@section('content')
@endsection

@section('script')

@if ($errors->any())
	@php
		$currentRoute = session('route_name');

		dump($currentRoute);

	@endphp
	@if ($currentRoute === 'user.edit')
		<script>
				document.getElementById("js_edit_account_modal").classList.remove("hidden")
				document.querySelector(".bg").classList.remove("hidden")

				let form = document.querySelector('.js_edit_account_form');
				let action = form.getAttribute('action');
				let id = document.querySelector(".js_account_id_input").value;
				console.log(id);
				
				action = action.replace(':id', id);
				form.setAttribute('action', action)

		</script>
	
	@endif

@endif

<script src="{{ mix("js/account_block.js")}}"></script>
@endsection