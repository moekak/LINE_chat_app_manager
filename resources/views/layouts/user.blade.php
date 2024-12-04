@extends('layouts.default')

@section('title2')
    <h2>Dashboard</h2><p></p>
@endsection
@section('main')
<div class="dashboard__wrapper-main bg-lightgray">
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
                          <th scope="col"></th>
                          <th scope="col">ユーザーID</th>
                          <th scope="col">メッセージ受信数</th>
                          <th scope="col">作成日時</th>
                          <th scope="col">編集日時</th>
                          <th scope="col">管理</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                          <td w20>U816cc8212a3ede3d46f5822903f65d55</td>
                          <td>2</td>
                          <td>2024-08-09 15:03:21</td>
                          <td>2024-08-09 15:03:21</td>
                          <td class="operation">
                            <button class="operation_icon"><img src="{{asset("img/icons8-message-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                            <button class="operation_icon"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                          </td>
                        </tr>
                        
                      </tbody>
                    </table>
          </div>
  
          
    </div>
</div>
    
@endsection

@section('content')
    
@endsection