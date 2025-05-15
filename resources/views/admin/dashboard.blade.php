@extends('layouts.default')


@section('main')
      <input type='hidden' value='{{$user->id}}' id='js_admin_account_id'>
      <div class='dashboard__wrapper-main bg-lightgray'>
            @php
                  // セッションに 'success' キーが存在するかチェック
                  if (session('success')) {
                        // 成功メッセージがある場合、アラートを表示
                        $style = 'block';
                        $text = session('success');
                  } else {
                        // 成功メッセージがない場合、アラートを非表示に
                        $style = 'none';
                        $text = '';
                  }
            @endphp
            <div class='alert alert-success alert-dismissible fade show success_alert' style='display: {{$style}}; width: max-content;' role='alert' id='js_alert_success'>
                  {{$text}}
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>     

            <div class='dashboard__wrapper-table-area'>
                  <div class='dashboard__wrapper-table-box p-20'>
                        <div class='dashboard__wrapper-table-ttl'>
                              <p>使用中アカウント一覧</p>
                        </div>  
                  </div>
                  <div class='dashboard__wrapper-table' data-status-id='1'>
                        <table class='table table-striped'>
                              <thead>
                                    <tr>
                                          <th scope='col'>アカウント名</th>
                                          <th scope='col'>未読数</th>
                                          <th scope='col'>ステータス</th>
                                          <th scope='col'>最新受信日</th>
                                          <th scope='col'>作成日時</th>
                                          <th scope='col' style='width: 20px;'>管理</th>
                                    </tr>
                              </thead>
                              <tbody class='js_table js_parentEl1'>
                                    
                                    @foreach ($active_accounts as $account)
                                          <tr class='js_account_id' data-id={{$account['entity_uuid']}}>
                                                <td w20 class='account_name' data-simplebar>{{$account['account_name']}}</td>
                                                <td class=' text-center total_message-count'>
                                                      <div class='message_count js_mesage_count js_total_count' style='display: {{ $account['unread_count'] > 0 ? 'flex' : 'none' }}; font-weight: bold;'>
                                                            {{ $account['unread_count'] }}
                                                      </div>
                                                </td>
                                                <td data-id={{$account['id']}} class='js_status' style='color: #008000; cursor: pointer;'>
                                                      <div class='btn-group'>
                                                            <button class='btn btn-secondary btn-sm dropdown-toggle js_status_btn' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                                  使用中
                                                            </button>
                                                            <ul class='dropdown-menu'>
                                                                  @foreach ($account_status as $other_status)
                                                                        @if ($other_status->status !== '使用中')
                                                                              <li class='dropdown-item js_status_choices' data-current-status='使用中' data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account['id']}}}>{{$other_status->status}}</li> 
                                                                        @endif
                                                                  @endforeach
                                                            </ul>
                                                      </div>
                                                </td>
                                                <td class='js_latest_message_date'>{{$account['latest_message_date'] ?? ''}}</td>
                                                <td>{{$account['created_at']->format('Y-m-d H:i')}}</td>
                                                <td>
                                                      <div class="operation">
                                                            <a href='{{route('account.show', ['id' => $account['id']])}}'><button class='operation_icon user-list_icon' title='リスト'><img src='{{asset('img/icons8-user-24.png')}}' alt=''></button></a>
                                                            <button class='operation_icon js_send_message_btn relative' data-id=<?= $account['id']?> title='一斉送信'><img src='{{asset('img/icons8-send-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_edit_account_btn relative' data-id=<?= $account['id']?> title='情報'><img src='{{asset('img/icons8-edit-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_delete_account_btn relative' type='submit' data-id=<?= $account['id']?> data-name={{$account['account_name']}} title='削除'><img src='{{asset('img/icons8-delete-24.png')}}' alt=''></button>
                                                      </div>
                                                      
                                                </td>
                                          </tr>
                                    @endforeach
                              
                              </tbody>
                        </table>
                        <div class='loader-container'>
                              <div class='loader2 js_loader hidden'></div>
                        </div>
                  </div> 
            </div>
            <div class='dashboard__wrapper-table-area'>

                  <div class='dashboard__wrapper-table-box p-20'>
                        <div class='dashboard__wrapper-table-ttl'>
                              <p>未使用アカウント一覧</p>
                        </div>  
                  </div>
                  <div class='dashboard__wrapper-table' data-status-id='2'>
                        <table class='table table-striped'>
                              <thead>
                                    <tr>
                                          <th scope='col'>アカウント名</th>
                                          <th scope='col'>未読数</th>
                                          <th scope='col'>ステータス</th>
                                          <th scope='col'>最新受信日</th>
                                          <th scope='col'>作成日時</th>
                                          <th scope='col'>管理</th>
                                    </tr>
                              </thead>
                              <tbody class='js_table js_parentEl2'>
                                    
                                    @foreach ($inactive_accounts as $account)
                                          <tr class='js_account_id' data-id={{$account['entity_uuid']}}>
                                                <td w20 class='account_name' data-simplebar>{{$account['account_name']}}</td>

                                                <td class=' text-center total_message-count'>
                                                      <div class='message_count js_mesage_count js_total_count' style='display: {{ $account['unread_count'] > 0 ? 'flex' : 'none' }}; font-weight: bold;'>
                                                            {{ $account['unread_count'] }}
                                                      </div>
                                                </td>
                                                <td data-id={{$account['id']}} class='js_status' style='color: #008000; cursor: pointer;'>
                                                      <div class='btn-group'>
                                                            <button class='btn btn-secondary btn-sm dropdown-toggle js_status_btn' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                                  未使用
                                                            </button>
                                                            <ul class='dropdown-menu'>
                                                                  @foreach ($account_status as $other_status)
                                                                        @if ($other_status->status !== '未使用')
                                                                              <li class='dropdown-item js_status_choices' data-current-status='未使用' data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account['id']}}}>{{$other_status->status}}</li> 
                                                                        @endif
                                                                  @endforeach
                                                            </ul>
                                                      </div>
                                                </td>
                                                <td class='js_latest_message_date'>{{$account['latest_message_date'] ?? ''}}</td>
                                                <td>{{$account['created_at']->format('Y-m-d H:i')}}</td>
                                                <td>
                                                      <div class="operation">
                                                            <a href='{{route('account.show', ['id' => $account['id']])}}'><button class='operation_icon user-list_icon' title='リスト'><img src='{{asset('img/icons8-user-24.png')}}' alt=''></button></a>
                                                            <button class='operation_icon js_send_message_btn relative' data-id=<?= $account['id']?> title='一斉送信'><img src='{{asset('img/icons8-send-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_edit_account_btn relative' data-id=<?= $account['id']?> title='情報'><img src='{{asset('img/icons8-edit-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_delete_account_btn relative' type='submit' data-id=<?= $account['id']?> data-name={{$account['account_name']}} title='削除'><img src='{{asset('img/icons8-delete-24.png')}}' alt=''></button>
                                                      </div>
                                                </td>
                                          </tr>
                                    @endforeach
                              
                              </tbody>
                        </table>
                        <div class='loader-container'>
                              <div class='loader2 js_loader hidden'></div>
                        </div>
                  </div> 
            </div>
            <div class='dashboard__wrapper-table-area'>

                  <div class='dashboard__wrapper-table-box p-20'>
                        <div class='dashboard__wrapper-table-ttl'>
                              <p>停止アカウント一覧</p>
                        </div>  
                  </div>
                  <div class='dashboard__wrapper-table' data-status-id='3'>
                        <table class='table table-striped'>
                              <thead>
                                    <tr>
                                          <th scope='col'>アカウント名</th>
                                          <th scope='col'>未読数</th>
                                          <th scope='col'>ステータス</th>
                                          <th scope='col'>最新受信日</th>
                                          <th scope='col'>作成日時</th>
                                          <th scope='col'>管理</th>
                                    </tr>
                              </thead>
                              <tbody class='js_table js_parentEl3' >
                                    
                                    @foreach ($suspended_accounts as $account)
                                          <tr class='js_account_id' data-id={{$account['entity_uuid']}}>
                                                <td w20 class='account_name' data-simplebar>{{$account['account_name']}}</td>

                                                <td class=' text-center total_message-count'>
                                                      <div class='message_count js_mesage_count js_total_count' style='display: {{ $account['unread_count'] > 0 ? 'flex' : 'none' }}; font-weight: bold;'>
                                                            {{ $account['unread_count'] }}
                                                      </div>
                                                </td>
                                                <td data-id={{$account['id']}} class='js_status' style='color: #008000; cursor: pointer;'>
                                                      <div class='btn-group'>
                                                            <button class='btn btn-secondary btn-sm dropdown-toggle js_status_btn' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                                  停止
                                                            </button>
                                                            <ul class='dropdown-menu'>
                                                                  @foreach ($account_status as $other_status)
                                                                        @if ($other_status->status !== '停止')
                                                                              <li class='dropdown-item js_status_choices' data-current-status='停止' data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account['id']}}}>{{$other_status->status}}</li> 
                                                                        @endif
                                                                  @endforeach
                                                            </ul>
                                                      </div>
                                                </td>
                                                <td class='js_latest_message_date'>{{$account['latest_message_date'] ?? ''}}</td>
                                                <td>{{$account['created_at']->format('Y-m-d H:i')}}</td>
                                                <td>
                                                      <div class="operation">
                                                            <a href='{{route('account.show', ['id' => $account['id']])}}'><button class='operation_icon user-list_icon' title='リスト'><img src='{{asset('img/icons8-user-24.png')}}' alt=''></button></a>
                                                            <button class='operation_icon js_send_message_btn relative' data-id=<?= $account['id']?> title='一斉送信'><img src='{{asset('img/icons8-send-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_edit_account_btn relative' data-id=<?= $account['id']?> title='情報'><img src='{{asset('img/icons8-edit-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_delete_account_btn relative' type='submit' data-id=<?= $account['id']?> data-name={{$account['account_name']}} title='削除'><img src='{{asset('img/icons8-delete-24.png')}}' alt=''></button>
                                                      </div>
                                                </td>
                                          </tr>
                                    @endforeach
                              
                              </tbody>
                        </table>
                        <div class='loader-container'>
                              <div class='loader2 js_loader hidden'></div>
                        </div>
                  </div> 
            </div>
            <div class='dashboard__wrapper-table-area'>

                  <div class='dashboard__wrapper-table-box p-20'>
                        <div class='dashboard__wrapper-table-ttl'>
                              <p>バンアカウント一覧</p>
                        </div>  
                  </div>
                  <div class='dashboard__wrapper-table' data-status-id='4'>
                        <table class='table table-striped'>
                              <thead>
                                    <tr>
                                          <th scope='col'>アカウント名</th>
                                          <th scope='col'>未読数</th>
                                          <th scope='col'>ステータス</th>
                                          <th scope='col'>最新受信日</th>
                                          <th scope='col'>作成日時</th>
                                          <th scope='col'>管理</th>
                                    </tr>
                              </thead>
                              <tbody class='js_table js_parentEl4'>
                                    
                                    @foreach ($banned_accounts as $account)
                                          <tr class='js_account_id' data-id={{$account['entity_uuid']}}>
                                                <td w20 class='account_name' data-simplebar>{{$account['account_name']}}</td>

                                                <td class=' text-center total_message-count'>
                                                      <div class='message_count js_mesage_count js_total_count' style='display: {{ $account['unread_count'] > 0 ? 'flex' : 'none' }}; font-weight: bold;'>
                                                            {{ $account['unread_count'] }}
                                                      </div>
                                                </td>
                                                <td data-id={{$account['id']}} class='js_status' style='color: #008000; cursor: pointer;'>
                                                      <div class='btn-group'>
                                                            <button class='btn btn-secondary btn-sm dropdown-toggle js_status_btn' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                                  バン
                                                            </button>
                                                            <ul class='dropdown-menu'>
                                                                  @foreach ($account_status as $other_status)
                                                                        @if ($other_status->status !== 'バン')
                                                                              <li class='dropdown-item js_status_choices' data-current-status='バン' data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account['id']}}}>{{$other_status->status}}</li> 
                                                                        @endif
                                                                  @endforeach
                                                            </ul>
                                                      </div>
                                                </td>
                                                <td class='js_latest_message_date'>{{$account['latest_message_date'] ?? ''}}</td>
                                                <td>{{$account['created_at']->format('Y-m-d H:i')}}</td>
                                                <td>
                                                      <div class="operation">
                                                            <a href='{{route('account.show', ['id' => $account['id']])}}'><button class='operation_icon user-list_icon' title='リスト'><img src='{{asset('img/icons8-user-24.png')}}' alt=''></button></a>
                                                            <button class='operation_icon js_send_message_btn relative' data-id=<?= $account['id']?> title='一斉送信'><img src='{{asset('img/icons8-send-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_edit_account_btn relative' data-id=<?= $account['id']?> title='情報'><img src='{{asset('img/icons8-edit-24.png')}}' alt=''></button>
                                                            <button class='operation_icon js_delete_account_btn relative' type='submit' data-id=<?= $account['id']?> data-name={{$account['account_name']}} title='削除'><img src='{{asset('img/icons8-delete-24.png')}}' alt=''></button>
                                                      </div>
                                                </td>
                                          </tr>
                                    @endforeach
                              
                              </tbody>
                        </table>
                        <div class='loader-container'>
                              <div class='loader2 js_loader hidden'></div>
                        </div>
                  </div> 
            </div>
      </div>

@endsection

@section('modal')
      @include('modal.lineAccount.lineAccount_add')
      @include('modal.lineAccount.lineAccount_edit')
      @include('modal.broadcast.broadcast')
      @include('modal.broadcast.brooadcast_delete_confirmation')
      @include('modal.lineAccount.lineAccount_delete_confirmation')
      @include('modal.lineAccount.status_confirmation')
      @include('modal.lineText.line_text_update')
      @include('modal.crop.crop')
      @include('modal.test.test_sender')
      @include('modal.test.test_sender_add')

@endsection

@section('script')
      <script src='{{ mix('js/dashboard.js')}}'></script>
      <script src='{{ mix('js/broadcastMessage.js')}}'></script>
      <script src='{{ mix('js/test_sender_add.js')}}'></script>
      <script src='//cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js'></script>

      @if ($errors->any())
            @php
                  $currentRoute = session('route_name');
            @endphp
            @if ($currentRoute === 'account.create')
                  <script>
                        document.getElementById('js_create_account_modal').classList.remove('hidden')
                        document.querySelector('.bg').classList.remove('hidden')
                  </script>
            @elseif ($currentRoute === 'account.edit')
                  <script>
                        document.getElementById('js_edit_account_modal').classList.remove('hidden')
                        document.querySelector('.bg').classList.remove('hidden')

                        let form = document.querySelector('.js_edit_account_form');
                        let action = form.getAttribute('action');
                        action = action.replace('ID_PLACEHOLDER', document.querySelector('.js_account_id_input').value);
                        form.setAttribute('action', action)
                  </script>  
            @elseif ($currentRoute === 'lineMessage.create')
                  <script>
                        document.getElementById('js_update_message_modal').classList.remove('hidden')
                        document.querySelector('.bg').classList.remove('hidden')

                        let form = document.querySelector('.js_update_sendingMsg');
                        let action = form.getAttribute('action');
                        action = action.replace('ID_PLACEHOLDER', document.getElementById('js_admin_account_id').value);
                        form.setAttribute('action', action)
                  </script>  
            @endif
      @endif
@endsection