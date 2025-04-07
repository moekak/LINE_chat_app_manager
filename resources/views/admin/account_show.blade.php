@extends('layouts.default')
@section('style')
	<style>
		body{
			overflow: hidden;
			max-height: 100vh;
		}
		.dashboard__wrapper-table-area{
			padding: 40px 100px 20px 100px;
			height: 90vh;
			
		}
	</style>
@endsection
@section('main')
<input type="hidden" value="{{$user_uuid}}" id="js_admin_account_id">
<input type="hidden" value={{Route::current()->parameter('id');}} id="js_account_id">
<div class="dashboard__wrapper-main bg-lightgray">
	@php
		// セッションに 'success' キーが存在するかチェック
		if (session("success")) {
			// 成功メッセージがある場合、アラートを表示
			$style = "block";
			$text = session("success");
		} else {
			// 成功メッセージがない場合、アラートを非表示に
			$style = "none";
			$text = "";
		}
	@endphp
	<div class="alert alert-success alert-dismissible fade show success_alert" style="display: {{$style}}; width: max-content;" role="alert" id="js_alert_success">
		{{$text}}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>  
	<div class="dashboard__wrapper-table-area">
		<div class="dashboard__wrapper-table-box p-20">
			<div class="dashboard__wrapper-table-ttl">
				<p>ユーザー一覧</p>
			</div>  
		</div>
		<div class="dashboard__wrapper-table">
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">ユーザー名</th>
						<th scope="col">未読数</th>
						<th scope="col">最新受信日</th>
						<th scope="col">作成日時</th>
						<th scope="col">管理</th>
					</tr>
				</thead>
				<tbody class="js_table">
					@foreach ($chat_users as $chat_user)
						<tr data-id={{$chat_user["entity_uuid"]}} class="js_chatUser_id">
							<td w20 class="chat_user_name" data-simplebar><?= $chat_user["line_name"]?></td>
							
							<td  data-id={{$chat_user->id}}>
								<div class="message_count js_message_count" style="display: {{ $chat_user['unread_count'] > 0 ? 'flex' : 'none' }}; font-weight: bold;">
									{{ $chat_user["unread_count"]}}
								</div>
							</td>
							<td class="js_latest_message_date">{{$chat_user["latest_message_date"] ? $chat_user["latest_message_date"] : ""}}</td>
							<td><?= $chat_user["created_at"]->format('Y-m-d H:i') ?></td>
							<td class="operation">
								<form action="{{config('services.chat_api')}}" method="POST" class="js_redirect_form">
									<input type="hidden" name="admin_id" class="js_admin_el">
									<input type="hidden" name="user_id" class="js_user_el">
									<input type="hidden" name="token" class="js_token">
									<button type="submit" class="operation_icon js_redirect_btn" data-user-id={{$chat_user['id']}} data-admin-id={{$id}}><img src="{{ asset('img/icons8-message-24.png') }}" alt="メッセージ"></button>
								</form>
								<button class="operation_icon js_edit_user_btn" data-id={{$chat_user["id"]}}><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
								<button class="operation_icon js_block_btn" data-uuid="{{$chat_user["entity_uuid"]}}" data-name="{{$chat_user["line_name"]}}" data-id="{{$chat_user["id"]}}"><img src="{{asset("img/icons8-no-entry-24.png")}}" alt=""></button>
							</td>
						</tr>
					@endforeach
				</tbody>
				
			</table>
			<div class="loader-container">
				<div class="loader2 js_loader hidden"></div>
			</div>
			
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
	<form action="{{route("user.update", ["id"=> "ID_PLACEHOLDER"])}}" method="post" class="js_edit_account_form">
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
<section class="modal__container js_modal hidden" id="js_block_account_modal" style="width: 500px;">
	<h2 class="modal__container-ttl" style="color: red; font-weight: bold;">本当にこのユーザーをブロックしますか？</h2>
	<p>ユーザーをブロックすると、以下の機能が制限されます：<br>
		<li>メッセージの送受信</li>
		<li>一斉メッセージの送信</li>
		<br>
		ブロックを実行してもよろしいですか？
	</p>
	<div style="margin-top: 2px"></div>
	<p>ユーザー名：<span id="js_account_name"></span></p>
	<div class="delete_account-btn-container">
		<div class="cancel-btn btn-box js_delete_account_cancel">
			<button class="delete_account-btn js_block_cancel" readonly>キャンセル</button>
		</div>
		<form action="{{route('account.block', ['id' => 'ID_PLACEHOLDER'])}}" method="get" class="btn-box delete-btn js_block_account_from">
			@csrf
			<button class="delete_account-btn" type="submit" style="color: #fff;" readonly>ブロック</button>
		</form>
</div>
</section>


{{-- 初回あいさつモーダル --}}
<section class="modal__container js_modal broadcasting_message_modal hidden" id="js_messageSetting_modal" style="width: 530px;">
	<h3 class="modal__container-ttl">初回メッセージ設定</h3>
	<div class="alert alert-secondary" style="font-size: 14px;" role="alert">
		ユーザー名を挿入する際は、{名前}としてください。<br>
		例). {名前}さん、初めまして！
	</div>

	<div class="alert alert-danger alert_danger_container js_alert_danger hidden js_broadcast_error" role="alert">
		<ul>
			<li class="alert_danger js_error_txt">メッセージを入力して保存ボタンを押してください。<br> または画像を選択してください。</li> 
		</ul>   
	</div>  

	<div class="broadcast_message_area">
		<div class="broascast_message-list">
			<div id="accordion" class="js_accordion_wrapper">
		</div>
		<div class="mb-3 mt-3">
			<label for="formGroupExampleInput" class="form-label">本文 <span style="color: red; font-size: 13px;">※</span></label>
			<textarea class="form-control js_message_input" id="exampleFormControlTextarea1" rows="6" name="body">{{ old('body') }}</textarea>
			<div class="mt-3"></div>
			<input type="file" class="js_upload" id="fileInput" accept="image/png, image/jpeg">
		</div>
		<input type="hidden" name="admin_account_id" value={{Route::current()->parameter('id');}} id="js_greeting_account_id">
		<div class="broadcast_message_submit_btn-box">
			<button type="submit" class="modal__container-btn js_message_display_btn disabled_btn">追加</button>  
			<button type="submit" class="modal__container-btn js_message_submit_btn disabled_btn">保存</button>  
		</div>
	</div>      
</section>


{{-- 一斉配信モーダル閉じる前の確認 --}}
<section class="modal__container js_modal hidden" id="js_broadcast_confirm_modal" style="width: 500px;">
	<h2 class="modal__container-ttl" style="color: red; font-weight: bold;">設定画面を閉じてよろしいですか？</h2>
	<p>一斉配信設定画面を閉じてよろしいですか？閉じると、設定した全てのデータが完全に消去され、元に戻すことはできません。</p>
	<div style="margin-top: 15px"></div>
	<div class="delete_account-btn-container">
		<div class="btn-box">
				<button id="js_cancel_btn" readonly style="width: 100%;">キャンセル</button>
		</div>
		<div class="cancel-btn btn-box js_delete_account_from delete-btn ">
				<button style="color: #fff;width: 100%;" readonly ><a href="" style="width: 100%; display: block;">閉じる</a></button>
		</div>
	</div>
</section>
{{-- タイトル更新 --}}
<section class="modal__container js_modal hidden js_loader" id="js_create_title_modal">
	<h3 class="modal__container-ttl">タイトル表示変更</h3>
	@if ($errors->any())
	<div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
            <ul>
				@foreach ($errors->all() as $error)
				<li class="alert_danger">{{$error}}</li>
				@endforeach  
			</ul>   
	</div>  
	@endif
	<form action="{{ route('title.update')}}" method="post">
		@csrf
		<div class="mb-3">
			<label for="formGroupExampleInput" class="form-label">タイトル <span style="color: red; font-size: 13px;">※</span></label>
			<input type="text" class="form-control js_line_message_input" id="formGroupExampleInput" name="title" value="{{ isset($title) && isset($title['title']) ? $title['title'] : old('title') }}">
			<input type="hidden" class="form-control js_line_message_input" id="formGroupExampleInput"  name="admin_id" value={{ $title["admin_id"] ?? Route::current()->parameter('id')}}>
		</div>
		<button type="submit" class="modal__container-btn">保存</button>
	</form>
</section>


{{-- 画像のURL編集モーダル --}}
<section class="image_edit_modal hidden" id="js_image_edit_modal">
	<div class="crop_loader hidden"></div>
	<div class="preview_box">
		<div class="preview_box-top">
			<p>プレビュー</p>
			<small>※送信時に画像が劣化する場合があります。</small>
		</div>
		<div class="prview_box-area">
                  <div class="preview_box-img" id="image-container">
                        <img src="" alt="" id="image">
                  </div> 
            </div>
		<div class="preview_box-desc">
			<div>対応形式: .png/.jpg</div>
			<div>最大データ容量: 5MBまで</div>
		</div>
	</div>
	<div class="url_setting_area">
		<p class="url_setting_txt">タップ時アクションの利用</p>
		<div class="radio_btn">
			<div class="form-check">
				<input class="form-check-input" value="off" type="radio" name="choice" id="flexRadioDefault1" checked>
				<label class="form-check-label" for="flexRadioDefault1">利用しない</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" value="on" type="radio" name="choice" id="flexRadioDefault2">
				<label class="form-check-label" for="flexRadioDefault2">利用する</label>
			</div> 
		</div>
		<div class="url_setting_wrapper" id="js_url_setting">
			<div style="margin-top: 20px;"></div>
			<p class="url_setting_txt">URL設定</p>
			<input type="url" name="url" id="js_url_input" class="url_input" placeholder="https://example.com" maxlength="2048">
			<small class="hidden js_url_error" style="color: red; font-size: 12px; padding-top: 14px;">URLの形式にしてください</small><br>
			<small class="hidden js_image_error" style="color: red; font-size: 12px; padding-top: 14px;"></small>
			<div class="btn_area">
				<button id="js_changeImg_btn"><label for="fileInput" class="change_img">画像変更</label></button>
				<button id="js_change_area" class="disabled_btn">選択範囲確定</button>
			</div>
			<small style="color: gray;font-size: 12px;">※画像を変更する際は、タップ時アクションが設定されている場合設定が解除されます。</small><br>
			<div style="margin-top: 10px;"></div>
			<small style="color: gray; font-size: 12px; margin-top: 3px;">※範囲を選択してから「選択範囲確定」ボタンを押してください。</small><br>
			<div style="margin-top: 10px;"></div>
			<small style="color: gray;  font-size: 12px; margin-top: 3px;">※再度範囲を選択したい場合は、「選択範囲変更」ボタンを押してください。</small>  
		</div>
		<div style="margin-top: 14px;">
			<button class="preview_submit_btn disabled_btn" id="js_preview_submit_btn">送信</button>
		</div>
	</div>
</section>


{{-- 保存時テキスト保存 --}}
<section class="modal__container js_modal hidden js_loader" id="js_create_text_modal">
	<h3 class="modal__container-ttl">固定テキスト設定</h3>
	@if ($errors->any())
	<div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
            <ul>
			@foreach ($errors->all() as $error)
			<li class="alert_danger">{{$error}}</li>
			@endforeach  
		</ul>   
	</div>  
	@endif
	<form action="{{ route('lineDisplayText.store')}}" method="post">
		@csrf
		<div class="mb-3">
			<div class="radio_btn">
				<div class="form-check">
					<input class="form-check-input js_display_radio radio_button-style" value="1" type="radio" name="is_show" id="flexRadioDefault3" {{ old('is_show', isset($line_display_text) ? $line_display_text["is_show"] : '') == '1' ? 'checked' : '' }}>
					<label class="form-check-label radio_button-style" for="flexRadioDefault3">表示する</label>
				</div>
				<div class="form-check">
					<input class="form-check-input js_display_radio radio_button-style" value="0" type="radio" name="is_show" id="flexRadioDefault4" {{ old('is_show', isset($line_display_text) ? $line_display_text["is_show"] : '') == '0' ? 'checked' : '' }}>
					<label class="form-check-label radio_button-style" for="flexRadioDefault4">表示しない</label>
				</div> 
			</div>
			<div class="text_input-area js_create_text {{ old('is_show') == '1' || (isset($line_display_text) && $line_display_text["is_show"] == '1') || (!isset($line_display_text) && old('is_show') === null) ? '' : 'hidden' }}">
				<label for="formGroupExampleInput" class="form-label mt-3">テキスト <span style="color: red; font-size: 13px;">※</span></label>
				<input type="text" class="form-control js_line_text_input" id="formGroupExampleInput" name="text" value="{{ old('text') ? old("text") :  (isset($line_display_text["text"])? $line_display_text['text'] : "" )}}">
				<input type="hidden" class="form-control " id="formGroupExampleInput"  name="admin_id" value={{ $title["admin_id"] ?? Route::current()->parameter('id')}}>
			</div>
		</div>
		<button type="submit" class="modal__container-btn">保存</button>
	</form>
</section>

{{-- テンプレート作成モーダル --}}
<section class="modal__container template_modal js_modal js_loader hidden relative" id="js_template_modal">
	<div class="bg_temaplteModal hidden"></div>
	<!-- モーダルコンテナ -->
	<div>
		<!-- モーダルヘッダー -->
		<div class="modal-header">
			<h2>テンプレート作成</h2>
			{{-- <button class="close-btn">&times;</button> --}}
		</div>

		<!-- モーダルコンテンツ -->
		<div class="modal-content">
			<!-- タブメニュー -->
			<div class="tabs">
				<div class="tab active" id="js_tab_new">新規作成</div>
				<div class="tab" id="js_tab_edit">一覧・編集</div>
			</div>
			<!-- エラーコンテナ - タブメニューの直後、フォーム開始前に配置 -->
			<div class="form-validation-errors hidden" id="form-errors">
				<div class="error-header">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10"></circle>
						<line x1="12" y1="8" x2="12" y2="12"></line>
						<line x1="12" y1="16" x2="12.01" y2="16"></line>
					</svg>
					<span>以下のエラーを修正してください</span>
				</div>
				<ul class="error-list" id="js_error_list"></ul>
			</div>

			<!-- 成功ーコンテナ - タブメニューの直後、フォーム開始前に配置 -->
			<div class="form-validation-success hidden" id="form-success">
				<div class="success-header">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
						<polyline points="22 4 12 14.01 9 11.01"></polyline>
					</svg>
					<span>カテゴリーの追加に成功しました。</span>
				</div>
				<ul class="success-list" id="js_success_list">
					<!-- <li class="success-item">テンプレートが正常に作成されました</li> -->
				</ul>
			</div>
						
			<!-- 新規作成フォーム -->
			<form class="tab-content js_create_form">
				@csrf
				<div class="form-group">
					<label for="template-title">テンプレート名</label>
					<input type="text" class="template-title" placeholder="例: 挨拶文" name="template_name" max="255" required>
				</div>
				
				<div class="category-management">
					<label>カテゴリ</label>
					<div class="add-category">
						<input type="text" placeholder="新しいカテゴリを追加" name="category_name" id="js_category_input" max="255">
						<button class="btn btn-primary" id="js_category_add_btn" disabled>追加</button>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
						<label for="category-select">カテゴリを選択</label>
						<select class="category-select" id="category-select" name="category_id">
							<option value="" disabled selected>カテゴリを選択</option>
							{{-- <option value="greeting">挨拶</option>
							<option value="inquiry">問い合わせ</option>
							<option value="request">依頼</option> --}}
						</select>
						</div>
					</div>
				</div>

				<!-- コンテンツブロック管理エリア -->
				<div class="content-blocks" id="create-content-blocks">
				</div>
				
				<!-- 追加ボタン -->
				<div class="add-block-container">
					<button class="btn btn-light add-text" id="js_add_text">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
							<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
						</svg>
						テキストを追加
					</button>
					<button class="btn btn-light add-image" id="js_add_image">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
							<circle cx="8.5" cy="8.5" r="1.5"></circle>
							<polyline points="21 15 16 10 5 21"></polyline>
						</svg>
						画像を追加
					</button>
				</div>
				
				<!-- ボタン -->
				<div class="btn-container">
					<button class="btn btn-primary js_submit_template_btn" id="template_submit_btn" type="submit">保存</button>
				</div>
			</form>
			
			<!-- 一覧・編集タブ (初期状態では非表示) -->
			<div class="tab-content tab-edit" style="display: none;">
				<!-- カテゴリーフィルターを追加 -->
				<div class="category-filter-container">
					<div class="filter-title">カテゴリーでフィルター：</div>
					<div class="category-buttons">
					</div>
				</div>
				<div class="template-list">
					<!-- テンプレートアイテム -->
				</div>
				<!-- モーダルコンテンツ -->
				<div class="modal-content">
					<!-- エラーコンテナ -->
					<div class="form-validation-errors hidden" id="edit-form-errors">
						<div class="error-header">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<line x1="12" y1="8" x2="12" y2="12"></line>
								<line x1="12" y1="16" x2="12.01" y2="16"></line>
							</svg>
							<span>以下のエラーを修正してください</span>
						</div>
						<ul class="error-list" id="js_edit_error_list"></ul>
					</div>

					<!-- 成功ーコンテナ -->
					<div class="form-validation-success hidden" id="edit-form-success">
						<div class="success-header">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
								<polyline points="22 4 12 14.01 9 11.01"></polyline>
							</svg>
							<span>テンプレートの更新に成功しました。</span>
						</div>
						<ul class="success-list" id="js_edit_success_list"></ul>
					</div>
				</div>
			</div>
			
			<!-- 編集フォーム -->
			<form class="edit-template-form js_edit_form hidden" id="template-edit-form">
				@csrf
				<div class="form-group">
					<label for="edit-template-title">テンプレート名</label>
					<input type="text" class="template-title" id="edit-template-title" placeholder="例: 挨拶文" name="template_name" max="255" required>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
						<label for="edit-category-select">カテゴリを選択</label>
						<select id="edit-category-select" name="category_id" class="category-select">
							@foreach ($categories as $category)
								<option class="edit-category" value={{$category->id}} >{{$category->category_name}}</option>
							@endforeach
							
						</select>
						</div>
					</div>
				</div>

				<!-- コンテンツブロック管理エリア -->
				<div class="content-blocks" id="edit-content-blocks">
					<!-- ここにテンプレートの既存コンテンツブロックが表示されます -->
				</div>
				
				<!-- 追加ボタン -->
				<div class="add-block-container">
					<button type="button" class="btn btn-light add-text" id="js_add_text">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
							<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
						</svg>
						テキストを追加
					</button>
					<button type="button" class="btn btn-light add-image" id="js_add_image">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
							<circle cx="8.5" cy="8.5" r="1.5"></circle>
							<polyline points="21 15 16 10 5 21"></polyline>
						</svg>
						画像を追加
					</button>
				</div>
				<input type="hidden" name="template_id" value="" id="js_template_id">
				<input type="hidden" name="group_id" value="" id="js_group_id">
				
				<!-- ボタン -->
				<div class="btn-container">
					<button class="btn btn-cancel" id="js_cancel_edit_btn" type="button">キャンセル</button>
					<button class="btn btn-primary js_submit_template_btn" id="js_update_template_btn" type="submit">更新</button>
				</div>
			</form>
		</div>
	</div>
</section>

@endsection

@section('script')
@if ($errors->any())
	@php
		$currentRoute = session('route_name');
	@endphp
	@if ($currentRoute === 'user.edit')
		<script>
				document.getElementById("js_edit_account_modal").classList.remove("hidden")
				document.querySelector(".bg").classList.remove("hidden")

				let form = document.querySelector('.js_edit_account_form');
				let action = form.getAttribute('action');
				let id = document.querySelector(".js_account_id_input").value;

				action = action.replace('ID_PLACEHOLDER', id);
				form.setAttribute('action', action)
		</script>
	@elseif($currentRoute === 'title.create')
		<script>
			document.getElementById("js_create_title_modal").classList.remove("hidden")
			document.querySelector(".bg").classList.remove("hidden")
		</script>
	@elseif($currentRoute === 'lineDisplayText.store')
	<script>
		document.getElementById("js_create_text_modal").classList.remove("hidden")
		document.querySelector(".bg").classList.remove("hidden")
		const radioBtns = document.querySelectorAll(".js_display_radio")
		const textInput = document.querySelector(".js_create_text")
		const textElement = document.querySelector(".js_line_text_input")

		radioBtns.forEach((radioBtn)=>{
				radioBtn.addEventListener("change", (e)=>{
				textInput.classList.toggle("hidden", e.target.value === "0")
				if(e.target.value === "0"){
					textElement.value = ""
				}
			})
			
		})
	</script>
	@endif
@endif

<script src="{{ mix("js/account_show.js")}}"></script>
<script src="{{ mix("js/greetingMessage.js")}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
@endsection