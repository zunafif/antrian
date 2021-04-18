@extends('admin/layout/master')
@section('stylesheet')
<style>
select, select.form-control {
    appearance: auto !important;
    -moz-appearance: auto !important;;
    -webkit-appearance: auto !important;;
}
</style>
@endsection
@section('content')  
<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header card-header-primary" style="background: linear-gradient(60deg, #00B59D, #00584D);">
        <h3 class="card-title"><b>Manajemen Antrian - {{(isset($profile->name))? $profile->name.'('.$profile->code_alpha.')' : ''}}</b></h3>
        <input type="hidden" id="final_counter" value="{{$count_counter}}">
        <input type="hidden" id="current_counter" value="{{$count_counter_reg}}">
      </div>
      <div class="card-body">
        <div id="typography">
          <div class="card-title">
            
          </div>
          <table class="table table-stripped">
            <thead>
              <tr>
                <th>Loket</th>
                <th>Tanggal Kunjungan</th>
                <th style="text-align:center;">Antrian Kunjungan</th>
                <th style="text-align:center;">Next</th>
                <th style="text-align:center;">Skip</th>
              </tr>
            </thead>
            <tbody>

              @if($filter == 'all' || !isset($counter_reg_que->current_queue))
                
              @else
                
                @foreach($counter_reg as $key => $val)
                  <tr style="background-color:{{($val->emergency == 1)? '#e74c3c' : 'white' }};color:{{($val->emergency == 1)? 'white' : 'black' }}">
                    <td>
                      <h3>
                        <b>
                          <!-- ({{$val->code_alpha}}){{$val->counter_name}} -->
                         ({{$val->name}}) {{$val->description}}
			</b>
                      </h3>
                    </td>
                    <td>{{$val->date_visit}}</td>
                    @if($counter_reg_que->current_queue == 0)
                      <input type="hidden" id="ready" value="ready">
                      <td colspan="4"><button class="btn btn-danger ready-button" onclick="javascript:ready({{$val->counter_type}},{{$val->counter_id}})">READY</button></td>
                    @else
                      <input type="hidden" id="ready" value="ready">
                        @if($val->counter_id == $counter_id)
                          <td style="text-align:center;font-weight:bold;font-size:20px;">
                          <span id="queue_number_{{$val->counter_id.'-'.$val->counter_type}}">{{$val->current_queue}}</span>
                          <br>
                          <br>
                          <span style="font-weight:normal !important; font-size:16px !important" id="queue_left_{{$val->counter_id}}">-</span>
                          </td>
                        @else
                          <!-- <td style="text-align:center;font-weight:bold;font-size:20px;" id="queue_number_{{$val->counter_id.'-'.$val->counter_type}}">{{$val->current_queue}}</td> -->
                          <td style="text-align:center;font-weight:bold;font-size:20px;" >
                          <!-- <span id="queue_number_{{$val->emergency.'-'.$val->counter_type}}">{{$val->current_queue}}</span> -->
                          <!-- <span id="queue_number_{{$val->emergency.'-'.$val->counter_type}}">Belum Ambil Antrian</span> -->
                          <span id="queue_number_{{$val->counter_id.'-'.$val->counter_type}}">1</span>
                          <br>
                          <br>
                          <span style="font-weight:normal !important; font-size:16px !important" id="ext_queue_left_{{$val->counter_id}}">-</span>
                          </td>
                        @endif
                      <td style="text-align:center">
                        @if($val->counter_id == $counter_id)
                          <button class="btn btn-info" onclick="javascript:next({{$val->counter_type}},{{$val->counter_id}})" id="next_{{$val->counter_id.'-'.$val->counter_type}}"><i class="fa fa-play"></i> Next</button>
                        @else
                          <button class="btn btn-primary" onclick="javascript:extnext({{$val->counter_type}},{{$val->emergency}},{{$val->counter_id}},{{$counter_id}})" id="next_{{$val->counter_id.'-'.$val->counter_type}}"><i class="fa fa-play"></i> Next</button>
                        @endif
                      </td>
                      <td style="text-align:center">
                        @if($val->counter_id == $counter_id)
                          <button class="btn btn-success" onclick="javascript:skip({{$val->counter_type}},{{$val->counter_id}})" id="skip_{{$val->counter_id.'-'.$val->counter_type}}"><i class="fa fa-forward"></i> Skip</button>
                        @else
                        <button class="btn btn-primary" onclick="javascript:extskip({{$val->counter_type}},{{$val->emergency}},{{$val->counter_id}},{{$counter_id}})" id="skip_{{$val->counter_id.'-'.$val->counter_type}}"><i class="fa fa-forward"></i> Skip</button>
                        @endif
                      </td>
                    @endif
                  </tr>
                @endforeach
              @endif

            </tbody>
          </table>
        </div>
      </div>
    </div>
    <form role="form" method="get" action="{{ route('queuefov2.read') }}">
      <div class="row" style="margin-left:0px;margin-right:0px;">
        <i class="material-icons text-danger">filter_alt</i>
          {{ csrf_field() }}
          <div class="col-lg-3 col-md-6 col-sm-6">
            <select name="filter" id="filter" class="form-control">
              <option value="" {{($filter == 'all')? 'selected':''}}>Pilih terlebih dahulu</option>
              @foreach($counter as $data)
                  <option value="{{$data->id.'-'.$data->counter_type.'-'.$data->emergency}}" {{($data->id == $filter)? 'selected':''}}>{{$data->name}} ({{$data->code_alpha}})</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-1 col-md-1 col-sm-1">
            <input type="submit" class="btn btn-success" value="filter">
          </div>
        
      </div>
    </form>
  </div>
</div>
<input type="hidden" id="disable-button" value="0">
@endsection

@section('javascript')
<script>
    $(document).ready(function(){
      var filter = $('#filter').val();
      if({{$filter}} == 'all'){

      }else{
        setInterval(function(){checkData(filter);}, 2000);
      }

      var final_counter = $('#final_counter').val();
      var current_counter = $('#current_counter').val();
      
      if((current_counter != final_counter) && filter != 'all'){
        setInterval(function(){checkCounter(filter);}, 2000);
      }else{

      }
      
      setInterval(function(){checkPar(filter);}, 2000);
      
      setInterval(function(){checkEmergency(filter);}, 1000);

      if({{$filter}} != 'all'){
        setInterval(function(){checkQueue(filter);}, 1000);
      }
    })

    function checkEmergency(data){
      var id = '';
      var v_counter_id = '';
      var v_counter_type = '';
      var v_emergency = '';
      if(data != ''){
        id = data.split("-");
        v_counter_type = id[1];
        v_counter_id = id[0];
        v_emergency = id[2];
        extQueue(v_counter_id,v_counter_type,v_emergency);
      }else{
        v_counter_type = 'all';
        v_counter_id = 'all';
      }
      
    }

    function checkQueue(data){
      split = data.split("-");
      var text = "Antrian Belum Terlayani";
      $.ajax({
        method: "POST",
        url: "{{route('queuefov2.checkQueue')}}",
        data: {_token:"{{csrf_token()}}", counter_id: split[0]},
        success: function(data){
          
          $('#queue_left_'+split[0]).text('');
          $('#queue_left_'+split[0]).text(data.queue_left+' '+text);
        }
      })
    }

    function extQueue(id,type,emergency){
      text = ' Antrian Belum Terlayani';
      $.ajax({
        method: "POST",
        url: "{{route('queuefov2.checkext')}}",
        data: {_token:"{{csrf_token()}}", counter_id:id, counter_type:type, counter_emergency:emergency},
        success: function(data){
          for (let i = 0; i < data.queue_left.length; i++) {
            $('#ext_queue_left_'+data.queue_left[i]['id']).text('');
            $('#ext_queue_left_'+data.queue_left[i]['id']).text(data.queue_left[i]['queue_left']+text);
          }
        }
      });
    }

    function checkPar(){
      if($('.par-notif').val() == 0){
        $('.modal').hide();
      }else{
        $('.modal').show();
      }
    }

    function next(type,counter){
      console.log(type,counter);
      $('#disable-button').val(0);
      // console.log($('#disable-button').val());
        if(counter == 0){
            alert('Untuk Loket Umum, Pilih Filter Loket Terlebih Dahulu');
        }else{
            // console.log(type,counter);
            var fix_counter = '';
            if(type == 2){
              fix_counter = 0;
            }
            var queue = $('#queue_number_'+counter+'-'+type).text();
            $.ajax({
              url: "{{route('queuefov2.next')}}",
              method: "POST",
              data: {_token:"{{csrf_token()}}",counter_type:type,counter_id:counter,common_counter: fix_counter,queue:queue},
              success: function(data){
                console.log(data);
                if(data.count == null){
                    alert('Antrian Sudah Habis');
                    disable_button(type,counter);
                    // location.reload();
                }
                if(data.result != null){
                  var filter = $('#filter').val();
                  var queue = $('#queue_number_'+counter+'-'+type).text(data.result.queue_number);
                  
                }
              }
            })
        }
    }
    function skip(type,counter){
      $('#disable-button').val(0);
        if(counter == 0){
            alert('Untuk Loket Umum, Pilih Filter Loket Terlebih Dahulu');
        }else{
            var fix_counter = '';
            if(type == 2){
              fix_counter = 0;
            }
            var queue = $('#queue_number_'+counter+'-'+type).text();
            $.ajax({
              url: "{{route('queuefov2.skip')}}",
              method: "POST",
              data: {_token:"{{csrf_token()}}",counter_type:type,counter_id:counter,common_counter: fix_counter,queue:queue},
              success: function(data){
                if(data.count == null){
                    alert('Antrian Sudah Habis');
                    disable_button(type,counter);
                    // location.reload();
                }
                if(data.result != null){
                  var filter = $('#filter').val();
                  var queue = $('#queue_number_'+counter+'-'+type).text(data.result.queue_number);
                }
              }
            })
        }
    }
    
    function disable_button(type,counter){
      $('#next_'+counter+'-'+type).attr('disabled',true);
      $('#skip_'+counter+'-'+type).attr('disabled',true);
      $('#next_'+counter+'-'+type).removeClass('btn-primary',true);
      $('#skip_'+counter+'-'+type).removeClass('btn-success',true);
      $('#next_'+counter+'-'+type).addClass('btn-default',true);
      $('#skip_'+counter+'-'+type).addClass('btn-default',true);
      $('#disable-button').val(1);
    }

    function enable_button(type,counter){
      par = $('#next_'+counter+'-'+type).attr('disabled');
      if(par === 'disabled'){
        $('.par-notif').val(1);
        $('#next_'+counter+'-'+type).attr('disabled',false);
        $('#skip_'+counter+'-'+type).attr('disabled',false);
        $('#next_'+counter+'-'+type).removeClass('btn-default',true);
        $('#skip_'+counter+'-'+type).removeClass('btn-default',true);
        $('#next_'+counter+'-'+type).addClass('btn-primary',true);
        $('#skip_'+counter+'-'+type).addClass('btn-success',true);
      }else{
        
      }
    }
    function checkData(data){
      var id = '';
      var v_counter_id = '';
      var v_counter_type = '';
      if(data != ''){
        id = data.split("-");
        v_counter_type = id[1];
        v_counter_id = id[0];
      }else{
        v_counter_type = 'all';
        v_counter_id = 'all';
      }
      var count_req_queue = '';
        $.ajax({
          url:"{{route('queuefov2.check')}}",
          method:'POST',
          data:{_token:'{{csrf_token()}}',counter_type:v_counter_type,counter_id:v_counter_id},
          success:function(data){
            // console.log(data);
            var count = false;
            if (data.counter_reg_queue === 'false') {
              // console.log('tidak');
            }else{
              // console.log('masuk');
              count = true;
            }
            
            if(data.result.length == 0){
              // alert("Tidak Ada Antrian")
            }else{
              for (let index = 0; index < data.result.length; index++) {
                var counter_id = '';
                var type = data.result[index].counter_type;
                if(type == 2){
                  counter_id = 0;
                }else{
                  counter_id = data.result[index].counter_id;
                }
                if($('#queue_number_'+counter_id+'-'+type).length == 0 && count == true){
                  if($('.ready-button').length == 0){
                    // console.log('reload 1');
                    // location.reload();
                    set_fo(type,counter_id);
                  }
                }else{
                  // console.log($('#disable-button').val());
                  if($('#disable-button').val() == 1){
                    enable_button(v_counter_type,v_counter_id);
                  }else{
                    // $('#queue_number_'+counter_id+'-'+type).text(data.result[index].queue_number);
                  }
                }
              }
            }
          }
        })
    }

    function ready(type,counter){
      if(counter == 0){
       var select = $('#filter').val();
       select = select.split('-');
       counter = select[0];
      }
      
      $.ajax({
        url:"{{route('queuefov2.ready')}}",
        method:"POST",
        data:{_token:"{{csrf_token()}}",counter_id:counter,counter_type:type},
        success:function(data){
          // console.log(data);
          if(data.count == null){
            alert('Tidak ada antrian');
            console.log('reload 2');
            location.reload();
          }
          if(data.result != 0){
            console.log('reload 3');
            location.reload();
          }
        }
      })
    }
    
    function set_fo(type,counter){
      $.ajax({
        url:"{{route('queuefov2.set_fo')}}",
        method:"POST",
        data:{_token:"{{csrf_token()}}",counter_id:counter,counter_type:counter},
        success:function(data){
          console.log(data);
          if(data.result != null){
            if($("input:contains('ready')")){
              location.reload();
            }else{
              
            }
            
          }
        }
      })
    }

    $('.btn-close-modal').on('click', function(){
      $('.par-notif').val(0);
    })

    function extnext(counter_type,emergency,counter_id,counter_next){
      var queue = $('#queue_number_'+counter_id+'-'+counter_type).text();
      $.ajax({
        url: "{{route('queuefov2.extnext')}}",
        method: 'POST',
        data: {_token:'{{csrf_token()}}', counter_type:counter_type, counter_id:counter_id, emergency:emergency, queue:queue, counter_next:counter_next},
        success: function(data){
          console.log(data);
          console.log('#queue_number_'+counter_id+'-'+counter_type);
          if(data.count != null){
            $('#queue_number_'+counter_id+'-'+counter_type).text(data.result.current_queue)
          }else{
            alert('Antrian Habis !')
          }
        }
      })
    }

    function extskip(counter_type,emergency,counter_id,counter_next){
      var queue = $('#queue_number_'+counter_id+'-'+counter_type).text();
      $.ajax({
        url: "{{route('queuefov2.extskip')}}",
        method: 'POST',
        data: {_token:'{{csrf_token()}}', counter_type:counter_type, counter_id:counter_id, emergency:emergency, queue:queue, counter_next:counter_next},
        success: function(data){
          console.log(data);
          console.log('#queue_number_'+counter_id+'-'+counter_type);
          if(data.count != null){
            $('#queue_number_'+counter_id+'-'+counter_type).text(data.result.current_queue)
          }else{
            alert('Antrian Habis !')
          }
        }
      })
    }

    function checkCounter(){
      var current_counter = $('#current_counter').val();
      var final_counter = $('#final_counter').val();
      $.ajax({
        url:"{{route('queuefov2.checkcounter')}}",
        method:'POST',
        data:{_token:'{{csrf_token()}}'},
        success:function(data){
          if(data.current_counter != current_counter){
            location.reload();
          }
        }
      })
    }
</script>
@endsection
<input type="hidden" value="0" class="par-notif">
<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Peringatan!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Ada antrian baru!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</body>

</html>
