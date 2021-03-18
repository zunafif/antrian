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
        <h3 class="card-title">Manajemen Antrian</h3>
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
                <th>Antrian Kunjungan</th>
                <th>Next</th>
                <th>Stop</th>
              </tr>
            </thead>
            <tbody>
              @if($filter == 'all' || !isset($counter_reg_que->current_queue))

              @else
              
                @foreach($counter_reg as $key => $val)
                  <tr style="background-color:white;">
                    <td>
                      <h3>
                          <!-- {{($val->counter_type == 2)? 'Loket Umum': $val->counter_name }} -->
                          {{$val->counter_name}}
                      </h3>
                    </td>
                    <td>{{$val->date_visit}}</td>
                    @if($counter_reg_que->current_queue == 0)
                      <td colspan="4"><button class="btn btn-danger ready-button" onclick="javascript:ready({{$val->counter_type}},{{$val->counter_id}})">READY</button></td>
                    @else
                    <td style="text-align:center;font-weight:bold;font-size:20px;" id="queue_number_{{$val->counter_id.'-'.$val->counter_type}}">{{$val->current_queue}}</td>
                    <td style="text-align:center">
                      <button class="btn btn-primary" onclick="javascript:next({{$val->counter_type}},{{($filter == 'all')? $val->counter_id : $filter }})" id="next_{{$val->counter_id.'-'.$val->counter_type}}"><i class="fa fa-play"></i> Next</button>
                    </td>
                    <td style="text-align:center">
                      <button class="btn btn-success" onclick="javascript:skip({{$val->counter_type}},{{($filter == 'all')? $val->counter_id : $filter }})" id="skip_{{$val->counter_id.'-'.$val->counter_type}}"><i class="fa fa-forward"></i> Skip</button>
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
    <form role="form" method="get" action="{{ route('queuefo.read') }}">
      <div class="row" style="margin-left:0px;margin-right:0px;">
        <i class="material-icons text-danger">filter_alt</i>
          {{ csrf_field() }}
          <div class="col-lg-3 col-md-6 col-sm-6">
            <select name="filter" id="filter" class="form-control">
              <option value="" {{($filter == 'all')? 'selected':''}}>Pilih terlebih dahulu</option>
              @foreach($counter as $data)
                  <option value="{{$data->id.'-'.$data->counter_type}}" {{($data->id == $filter)? 'selected':''}}>{{$data->name}}</option>
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
        // setInterval(function(){checkData(filter);}, 2000);
      }
      
    })
    function next(type,counter){
      // console.log(type,counter);
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
              url: "{{route('queuefo.next')}}",
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
              url: "{{route('queuefo.skip')}}",
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
      $('#disable-button').val(1);
    }

    function enable_button(type,counter){
      $('#next_'+counter+'-'+type).attr('disabled',false);
      $('#skip_'+counter+'-'+type).attr('disabled',false);
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
          url:"{{route('queuefo.check')}}",
          method:'POST',
          data:{_token:'{{csrf_token()}}',counter_type:v_counter_type,counter_id:v_counter_id},
          success:function(data){
            console.log(data);
            var count = false;
            if (data.counter_reg_queue == 'false') {
              // console.log('tidak');
            }else{
              // console.log('masuk');
              count = true;
            }
            
            if(data.result == null){
              alert("Tidak Ada Antrian")
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
                    location.reload();
                    set_fo(type,counter_id);
                  }
                }else{
                  // console.log($('#disable-button').val());
                  if($('#disable-button').val() == 1){
                    enable_button(v_counter_type,v_counter_id);
                  }else{
                    $('#queue_number_'+counter_id+'-'+type).text(data.result[index].queue_number);
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
        url:"{{route('queuefo.ready')}}",
        method:"POST",
        data:{_token:"{{csrf_token()}}",counter_id:counter,counter_type:type},
        success:function(data){
          // console.log(data);
          if(data.count == null){
            alert('Tidak ada antrian');
            location.reload();
          }
          if(data.result != 0){
            location.reload();
          }
        }
      })
    }
    
    function set_fo(type,counter){
      $.ajax({
        url:"{{route('queuefo.set_fo')}}",
        method:"POST",
        data:{_token:"{{csrf_token()}}",counter_id:counter,counter_type:counter},
        success:function(data){
          if(data.result != 0){
            location.reload();
          }
        }
      })
    }
</script>
@endsection
<div id="dialog-confirm" title="Peringatan !" style="display:none">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Apakah anda yakin untuk melewati antrian selanjutnya?</p>
</div>
</body>

</html>