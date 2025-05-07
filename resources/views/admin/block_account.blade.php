@extends('layouts.default')
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
					<th scope="col">ユーザー名</th>
					<th scope="col">最新受信日</th>
					<th scope="col">作成日時</th>
					<th scope="col">解除</th>
				</tr>
				</thead>
				<tbody class="js_table">
				@foreach ($block_lists as $chat_user)
					<tr data-id={{$chat_user["entity_uuid"]}} class="js_chatUser_id">
						<td w20><?= $chat_user["line_name"]?></td>
						<td class="js_latest_message_date">{{$chat_user["latest_message_date"] ? $chat_user["latest_message_date"] : ""}}</td>
						<td><?= $chat_user["created_at"]->format('Y-m-d H:i') ?></td>
						<td class="operation">
							<button class="operation_icon js_block_btn" data-uuid='{{$chat_user["uuid"]}}' data-name='{{$chat_user["line_name"]}}' data-id='{{$chat_user["id"]}}'><img src="{{asset("img/icons8-no-entry-24.png")}}" alt=""></button>
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
		<button type="submit" class="modal__container-btn">保存</button>
	</form>
</section>
{{-- ユーザーブロック確認モーダル --}}
<section class="modal__container js_modal hidden confirm_modal" id="js_block_account_modal" style="width: 500px;">
	<h2 class="modal__container-ttl" style="color: red; font-weight: bold;">本当にこのユーザーをブロック解除しますか？</h2>
	<p>ユーザーのブロックを解除すると、以下の機能が利用可能になります：<br>
		<li>メッセージの送受信</li>
		<li>一斉メッセージの送信</li>
		<br>
		ブロックを解除してもよろしいですか？
	</p>
	<div style="margin-top: 2px"></div>
	<p>ユーザー名：<span id="js_account_name"></span></p>
	<div class="delete_account-btn-container">
		<div class="cancel-btn btn-box js_delete_account_cancel">
			<button class="delete_account-btn js_block_cancel" readonly>キャンセル</button>
		</div>
		<form action="{{route('account.unblock', ['id' => 'ID_PLACEHOLDER'])}}" method="get" class="btn-box delete-btn js_block_account_from">
			@csrf
			<button class="delete_account-btn" type="submit" style="color: #fff;" readonly>ブロック解除</button>
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
				action = action.replace(':id', id);
				form.setAttribute('action', action)

		</script>
	
	@endif

@endif

<script src="{{ mix("js/account_block.js")}}"></script>
@endsection