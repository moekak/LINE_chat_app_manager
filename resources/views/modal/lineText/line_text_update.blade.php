
{{-- LINE送信文言変更モーダル --}}
<section class='modal__container js_modal js_loader hidden' id='js_update_message_modal'>
      <div class='modal-header'>
            <h2>LINE送信文言変更</h2>
      </div>
      <div class='modal-content'>
            @if ($errors->any())
                  <div class='alert alert-danger alert_danger_container js_alert_danger' role='alert'>
                        <ul>
                              @foreach ($errors->all() as $error)
                              <li class='alert_danger'>{{$error}}</li>
                              @endforeach  
                        </ul>   
                  </div>  
            @endif
            <form action='{{route('message.update', ['id' => 'ID_PLACEHOLDER'])}}'  method='post' class='js_update_sendingMsg'>
                  @csrf
                  <div class='mb-3'>
                        <label for='formGroupExampleInput' class='form-label'>文言 <span style='color: red; font-size: 13px;'>※</span></label>
                        <input type='text' class='form-control js_line_message_input' id='formGroupExampleInput' name='message' value='{{old('message')}}'>
                  </div>
                  <div style='text-align: right;'>
                        <button type='submit' class='modal__container-btn'>保存</button>  
                  </div>
                  
            </form>  
      </div>
      
</section>