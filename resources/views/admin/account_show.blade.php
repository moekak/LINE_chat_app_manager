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
<section class="modal__container template_modal js_modal js_loader" id="js_create_text_modal">
	<!-- モーダルコンテナ -->
	<div>
        <!-- モーダルヘッダー -->
        <div class="modal-header">
            <h2>テンプレート作成</h2>
            <button class="close-btn">&times;</button>
        </div>
        
        <!-- モーダルコンテンツ -->
        <div class="modal-content">
            <!-- タブメニュー -->
            <div class="tabs">
                <div class="tab active">新規作成</div>
                <div class="tab">一覧・編集</div>
            </div>
            
            <!-- 新規作成フォーム -->
            <div class="tab-content">
                <div class="form-group">
                    <label for="template-title">テンプレート名</label>
                    <input type="text" id="template-title" placeholder="例: 挨拶文">
                </div>
                
                <div class="category-management">
                    <label>カテゴリ</label>
                    <div class="category-list">
                        <div class="category-item">挨拶 <span class="delete-category">&times;</span></div>
                        <div class="category-item">問い合わせ <span class="delete-category">&times;</span></div>
                        <div class="category-item">依頼 <span class="delete-category">&times;</span></div>
                    </div>
                    <div class="add-category">
                        <input type="text" placeholder="新しいカテゴリを追加">
                        <button class="btn btn-primary">追加</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="category-select">カテゴリを選択</label>
                            <select id="category-select">
                                <option value="">カテゴリを選択</option>
                                <option value="greeting">挨拶</option>
                                <option value="inquiry">問い合わせ</option>
                                <option value="request">依頼</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- コンテンツブロック管理エリア -->
                <div class="content-blocks" id="content-blocks">
                    <!-- テキストブロック -->
                    <div class="content-block text-block" draggable="true" data-type="text" data-id="block-1">
                        <div class="block-header">
                            <div class="block-title">
                                <div class="handle">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="8" y1="6" x2="21" y2="6"></line>
                                        <line x1="8" y1="12" x2="21" y2="12"></line>
                                        <line x1="8" y1="18" x2="21" y2="18"></line>
                                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                    </svg>
                                </div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                テキスト
                            </div>
                            <div class="block-actions">
                                <button class="btn btn-icon btn-light delete-block">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <textarea class="block-textarea" placeholder="テキストを入力してください"></textarea>
                        </div>
                    </div>
                    
                    <!-- 画像ブロック -->
                    <div class="content-block image-block" draggable="true" data-type="image" data-id="block-2">
                        <div class="block-header">
                            <div class="block-title">
                                <div class="handle">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="8" y1="6" x2="21" y2="6"></line>
                                        <line x1="8" y1="12" x2="21" y2="12"></line>
                                        <line x1="8" y1="18" x2="21" y2="18"></line>
                                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                    </svg>
                                </div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                画像
                            </div>
                            <div class="block-actions">
                                <button class="btn btn-icon btn-light delete-block">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
							<div class="image-upload">
								<input type="file" class="file-input" accept="image/*">
								<div class="image-placeholder">
									<img src="{{asset("img/icons8-plus-50.png")}}" alt="">
									<p>ファイルの選択</p>
								</div>
							</div>
                        </div>
                    </div>
                </div>
                
                <!-- 追加ボタン -->
                <div class="add-block-container">
                    <button class="btn btn-light" id="add-text">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        テキストを追加
                    </button>
                    <button class="btn btn-light" id="add-image">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        画像を追加
                    </button>
                </div>
                
                <!-- プレビューセクション -->
                {{-- <div class="preview-section">
                    <div class="preview-header">
                        <h3>プレビュー</h3>
                        <div class="preview-toggle">
                            <span>自動更新</span>
                            <label class="toggle">
                                <input type="checkbox" id="auto-preview" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="preview-container" id="preview-container">
                        <div class="preview-message">
                            <div class="preview-text">お問い合わせありがとうございます。担当の者が確認次第、ご連絡いたします。</div>
                        </div>
                        <div class="preview-message">
                            <img src="/api/placeholder/400/300" alt="preview image" class="preview-image">
                        </div>
                    </div>
                </div> --}}
                
                <!-- ボタン -->
                <div class="btn-container">
                    <button class="btn btn-cancel">キャンセル</button>
                    <button class="btn btn-primary">保存</button>
                </div>
            </div>
            
            <!-- 一覧・編集タブ (初期状態では非表示) -->
			<div class="tab-content" style="display: none;">
                <div class="template-list">
                    <!-- テンプレートアイテム -->
                    <div class="template-item">
                        <div class="template-content">
                            <div class="template-title">挨拶文</div>
                            <div class="template-category">挨拶</div>
                            <div class="template-text">お問い合わせありがとうございます。担当の者が確認次第、ご連絡いたします。</div>
                        </div>
                        <div class="template-actions">
                            <button class="action-btn edit-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button class="action-btn delete-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="template-item">
                        <div class="template-content">
                            <div class="template-title">問い合わせ返信</div>
                            <div class="template-category">問い合わせ</div>
                            <div class="template-text">お問い合わせいただき、ありがとうございます。現在調査中ですので、もう少しお待ちください。</div>
                        </div>
                        <div class="template-actions">
                            <button class="action-btn edit-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button class="action-btn delete-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


@section('content')
@endsection

@section('script')
<script>
	  // 要素の参照を取得
	  	// const contentBlocks = document.getElementById('content-blocks');
        // const addTextBtn = document.getElementById('add-text');
        // const addImageBtn = document.getElementById('add-image');
        // const previewContainer = document.getElementById('preview-container');
        // // const autoPreviewToggle = document.getElementById('auto-preview');

        // // カウンター（ユニークなIDを生成するため）
        // let blockCounter = 3; // すでに2つのブロックがあるため3から開始

        // テキストブロックを追加
        // function addTextBlock() {
        //     const blockId = `block-${blockCounter++}`;
        //     const textBlock = document.createElement('div');
        //     textBlock.className = 'content-block text-block';
        //     textBlock.draggable = true;
        //     textBlock.dataset.type = 'text';
        //     textBlock.dataset.id = blockId;
            
        //     textBlock.innerHTML = `
        //         <div class="block-header">
        //             <div class="block-title">
        //                 <div class="handle">
        //                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        //                         <line x1="8" y1="6" x2="21" y2="6"></line>
        //                         <line x1="8" y1="12" x2="21" y2="12"></line>
        //                         <line x1="8" y1="18" x2="21" y2="18"></line>
        //                         <line x1="3" y1="6" x2="3.01" y2="6"></line>
        //                         <line x1="3" y1="12" x2="3.01" y2="12"></line>
        //                         <line x1="3" y1="18" x2="3.01" y2="18"></line>
        //                     </svg>
        //                 </div>
        //                 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        //                     <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
        //                     <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        //                 </svg>
        //                 テキスト
        //             </div>
        //             <div class="block-actions">
        //                 <button class="btn btn-icon btn-light delete-block">
        //                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        //                         <polyline points="3 6 5 6 21 6"></polyline>
        //                         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        //                         <line x1="10" y1="11" x2="10" y2="17"></line>
        //                         <line x1="14" y1="11" x2="14" y2="17"></line>
        //                     </svg>
        //                 </button>
        //             </div>
        //         </div>
        //         <div class="block-content">
        //             <textarea class="block-textarea" placeholder="テキストを入力してください"></textarea>
        //         </div>
        //     `;
            
        //     contentBlocks.appendChild(textBlock);
        //     // setupBlockListeners(textBlock);
        //     updatePreview();
        // }
		// 画像ブロックを追加
// function addImageBlock() {
//     const blockId = `block-${blockCounter++}`;
//     const imageBlock = document.createElement('div');
//     imageBlock.className = 'content-block image-block';
//     imageBlock.draggable = true;
//     imageBlock.dataset.type = 'image';
//     imageBlock.dataset.id = blockId;
    
//     imageBlock.innerHTML = `
//         <div class="block-header">
//             <div class="block-title">
//                 <div class="handle">
//                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
//                         <line x1="8" y1="6" x2="21" y2="6"></line>
//                         <line x1="8" y1="12" x2="21" y2="12"></line>
//                         <line x1="8" y1="18" x2="21" y2="18"></line>
//                         <line x1="3" y1="6" x2="3.01" y2="6"></line>
//                         <line x1="3" y1="12" x2="3.01" y2="12"></line>
//                         <line x1="3" y1="18" x2="3.01" y2="18"></line>
//                     </svg>
//                 </div>
//                 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
//                     <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
//                     <circle cx="8.5" cy="8.5" r="1.5"></circle>
//                     <polyline points="21 15 16 10 5 21"></polyline>
//                 </svg>
//                 画像
//             </div>
//             <div class="block-actions">
//                 <button class="btn btn-icon btn-light delete-block">
//                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
//                         <polyline points="3 6 5 6 21 6"></polyline>
//                         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
//                         <line x1="10" y1="11" x2="10" y2="17"></line>
//                         <line x1="14" y1="11" x2="14" y2="17"></line>
//                     </svg>
//                 </button>
//             </div>
//         </div>
//         <div class="block-content">
//             <div class="image-upload">
// 				<input type="file" class="file-input" accept="image/*">
// 				<div class="image-placeholder">
// 					<img src="/img/icons8-plus-50.png" alt="">
// 					<p>ファイルの選択</p>
// 				</div>
// 			</div>
// 		</div>
//     `;
    
//     contentBlocks.appendChild(imageBlock);
//     setupBlockListeners(imageBlock);
//     // setupImageUpload(imageBlock);
//     // updatePreview();
// }
	     // ブロックのイベントリスナーをセットアップ
		// function setupBlockListeners(block) {
        //     // 削除ボタンのイベントリスナー
        //     const deleteBtn = block.querySelector('.delete-block');
        //     deleteBtn.addEventListener('click', () => {
        //         block.remove();
        //         updatePreview();
        //     });
            
        //     // ドラッグ＆ドロップのイベントリスナー
        //     block.addEventListener('dragstart', dragStart);
        //     block.addEventListener('dragend', dragEnd);
            
        //     // テキストエリアのイベントリスナー（テキストブロックの場合）
        //     // if (block.classList.contains('text-block')) {
        //     //     const textarea = block.querySelector('.block-textarea');
        //     //     textarea.addEventListener('input', updatePreview);
        //     // }
        // }

        // // 画像アップロードの処理をセットアップ
        // function setupImageUpload(block) {
        //     const fileInput = block.querySelector('.file-input');
        //     const placeholder = block.querySelector('.image-placeholder');
            
        //     // プレースホルダーがクリックされたらファイル選択ダイアログを開く
        //     if (placeholder) {
        //         placeholder.addEventListener('click', () => {
        //             fileInput.click();
        //         });
        //     }
            
        //     // ファイルが選択されたら画像をプレビュー表示
        //     fileInput.addEventListener('change', () => {
        //         const file = fileInput.files[0];
        //         if (file) {
        //             const reader = new FileReader();
        //             reader.onload = (e) => {
        //                 const imgPreview = document.createElement('img');
        //                 imgPreview.src = e.target.result;
        //                 imgPreview.alt = '画像プレビュー';
        //                 imgPreview.className = 'image-preview';
                        
        //                 // プレースホルダーを画像に置き換え
        //                 const content = block.querySelector('.block-content');
        //                 content.innerHTML = '';
        //                 content.appendChild(fileInput); // ファイル入力は保持
        //                 content.appendChild(imgPreview);
                        
        //                 // プレビューを更新
        //                 updatePreview();
        //             };
        //             reader.readAsDataURL(file);
        //         }
        //     });
            
        //     // ドラッグ＆ドロップの処理
        //     if (placeholder) {
        //         placeholder.addEventListener('dragover', (e) => {
        //             e.preventDefault();
        //             placeholder.style.borderColor = 'var(--primary-color)';
        //             placeholder.style.backgroundColor = 'rgba(74, 108, 247, 0.05)';
        //         });
                
        //         placeholder.addEventListener('dragleave', () => {
        //             placeholder.style.borderColor = 'var(--border-color)';
        //             placeholder.style.backgroundColor = '';
        //         });
                
        //         placeholder.addEventListener('drop', (e) => {
        //             e.preventDefault();
        //             const file = e.dataTransfer.files[0];
        //             if (file && file.type.startsWith('image/')) {
        //                 fileInput.files = e.dataTransfer.files;
                        
        //                 const reader = new FileReader();
        //                 reader.onload = (e) => {
        //                     const imgPreview = document.createElement('img');
        //                     imgPreview.src = e.target.result;
        //                     imgPreview.alt = '画像プレビュー';
        //                     imgPreview.className = 'image-preview';
                            
        //                     // プレースホルダーを画像に置き換え
        //                     const content = block.querySelector('.block-content');
        //                     content.innerHTML = '';
        //                     content.appendChild(fileInput); // ファイル入力は保持
        //                     content.appendChild(imgPreview);
                            
        //                     // プレビューを更新
        //                     updatePreview();
        //                 };
        //                 reader.readAsDataURL(file);
        //             }
        //         });
        //     }
        // }

        // ドラッグ開始時の処理
        // function dragStart(e) {
        //     this.classList.add('dragging');
        //     e.dataTransfer.setData('text/plain', this.dataset.id);
        // }

        // // ドラッグ終了時の処理
        // function dragEnd() {
        //     this.classList.remove('dragging');
        // }

        // // ドラッグオーバー時の処理
        // function dragOver(e) {
        //     e.preventDefault();
        //     const draggingBlock = document.querySelector('.dragging');
        //     const targetBlock = this;
            
        //     if (draggingBlock !== targetBlock) {
        //         const container = document.getElementById('content-blocks');
        //         const blockRect = targetBlock.getBoundingClientRect();
        //         const mouseY = e.clientY;
                
        //         // マウスがブロックの上半分にあるかどうかを判定
        //         const isAboveHalf = mouseY < blockRect.top + blockRect.height / 2;
                

		// 		console.log(draggingBlock);
		// 		console.log(container);
		// 		console.log(targetBlock);
				
        //         if (isAboveHalf) {
        //             container.insertBefore(draggingBlock, targetBlock);
        //         } else {
        //             container.insertBefore(draggingBlock, targetBlock.nextSibling);
        //         }
        //     }
        // }

        // プレビューを更新する関数
        // function updatePreview() {
        //     if (!autoPreviewToggle.checked) return;
            
        //     const blocks = document.querySelectorAll('.content-block');
        //     const previewContainer = document.getElementById('preview-container');
            
        //     // プレビューをクリア
        //     previewContainer.innerHTML = '';
            
        //     // 各ブロックをプレビューに追加
        //     blocks.forEach(block => {
        //         const previewMessage = document.createElement('div');
        //         previewMessage.className = 'preview-message';
                
        //         if (block.dataset.type === 'text') {
        //             const textarea = block.querySelector('.block-textarea');
        //             const text = textarea.value;
                    
        //             const previewText = document.createElement('div');
        //             previewText.className = 'preview-text';
        //             previewText.textContent = text;
                    
        //             previewMessage.appendChild(previewText);
        //         } else if (block.dataset.type === 'image') {
        //             const imagePreview = block.querySelector('.image-preview');
                    
        //             if (imagePreview) {
        //                 const previewImage = document.createElement('img');
        //                 previewImage.className = 'preview-image';
        //                 previewImage.src = imagePreview.src;
        //                 previewImage.alt = 'プレビュー画像';
                        
        //                 previewMessage.appendChild(previewImage);
        //             }
        //         }
                
        //         previewContainer.appendChild(previewMessage);
        //     });
        // }

        // ボタンのクリックイベントをセットアップ
        // addTextBtn.addEventListener('click', addTextBlock);
        // addImageBtn.addEventListener('click', addImageBlock);
        
        // // 自動プレビュートグルのイベントリスナー
        // // autoPreviewToggle.addEventListener('change', updatePreview);
        
        // // 初期ブロックのリスナーをセットアップ
        // document.querySelectorAll('.content-block').forEach(block => {
        //     setupBlockListeners(block);
            
        //     // if (block.classList.contains('image-block')) {
        //     //     setupImageUpload(block);
        //     // }
        // });
        
        // // ドラッグ＆ドロップの対象エリアを設定
        // contentBlocks.addEventListener('dragover', (e) => {
        //     e.preventDefault();
        // });
        
        // ブロック間のドラッグ＆ドロップを可能にする
        // function setupSortableBlocks() {
        //     const blocks = document.querySelectorAll('.content-block');
            
        //     blocks.forEach(block => {
        //         block.addEventListener('dragover', dragOver);
        //     });
        // }
        
        // 初期化時にもドラッグ＆ドロップをセットアップ
        // setupSortableBlocks();
        
        // // 新しいブロックが追加されたときのためのMutationObserverを設定
        // const observer = new MutationObserver((mutations) => {
        //     mutations.forEach((mutation) => {
        //         if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
        //             setupSortableBlocks();
        //         }
        //     });
        // });
        
        // observer.observe(contentBlocks, { childList: true });
        
        // タブ切り替え機能
        // const tabs = document.querySelectorAll('.tab');
        // const tabContents = document.querySelectorAll('.tab-content');
        
        // tabs.forEach((tab, index) => {
        //     tab.addEventListener('click', () => {
        //         // アクティブタブの更新
        //         tabs.forEach(t => t.classList.remove('active'));
        //         tab.classList.add('active');
                
        //         // タブコンテンツの表示/非表示
        //         tabContents.forEach(content => {
        //             content.style.display = 'none';
        //         });
        //         tabContents[index].style.display = 'block';
        //     });
        // });
        
        // クローズボタン
        // const closeBtn = document.querySelector('.close-btn');
        // closeBtn.addEventListener('click', () => {
        //     document.querySelector('.modal-container').style.display = 'none';
        // });
    </script>
</script>

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