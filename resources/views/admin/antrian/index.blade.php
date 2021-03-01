@extends('admin/layout/master_blank')
<style>
      img{
        height: 100px;
        width: 100px;
      }

        body{
      /* background-image:url({{ asset('img/bg2.png') }})!important; */
      background-color:#2abb9b !important;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
      background-repeat:no-repeat;
      background-attachment:fixed;
      background-position:100% 100%;
      background-size:cover;
     }
     .que{
       font-size: 100px;
       font-weight: 100px;
     }
     .navbar .navbar-brand img {
      height: 20px;
    }

    .navbar .navbar-brand {
      padding-top: 0;
    }

    .navbar .nav-link {
      color: #444343!important;
    }

    .navbar .button-collapse {
      padding-top: 1px;
    }

    .card-intro .card-body {
      padding-top: 1.5rem;
      padding-bottom: 1.5rem;
      border-radius: 0 !important;
    }

    .card-intro .card-body h1 {
      margin-bottom: 0;
    }

    .card-intro {
      margin-top: 64px;
    }

    @media (max-width: 450px) {
      .card-intro {
        margin-top: 56px;
      }
    }

    @media (min-width: 1441px) {
      .card-intro {
        padding-left: 0 !important;
      }
    }
    </style>
    <link rel="icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <title>{{config('app.name')}}</title>
</head>
<body>
<nav class="navbar navbar-light white justify-content-between">
<a class="navbar-brand waves-effect" href="#">
          <img src="{{asset('img/rsbunda.png')}}" style="width:65px; height:60px;" alt="Logo">
        </a><h3 class=>Rumah Sakit Bunda</h3>
  <form class="form-inline my-1">
  <a href="{{url('/')}}" class="btn btn-teal"><i class="fa fa-home fa-2x"></i></a>
  </form>
</nav>

    <div class="col-12 ml-auto mt-4" style="padding-right:30px;padding-left:30px;">
        <div class="row">
          <div style="flex-direction:row;display:flex;width:100%;">
          <?php $index = 0;?>
          
          @foreach($counter as $val)    
              <div style="width:100%;display:block;padding:25px 10px;">
                <div style="background-color:white;padding:55px 7px;text-align:center;border-radius:10px;">
                  <h3 style="font-size:35px;margin-bottom:50px;"><b>{{$val->name}}</b></h3>
                  <h2 style="font-size:55px;font-weight:bold;" id="antrian_{{$val->id.'-'.$val->counter_type}}">
                    <?php $print = true?>
                    @if(count($counter_que) == 0)
                      {{'---'}}
                      <?php $print = false?>
                    @else
                      @foreach($counter_que as $data)  
                        @if($data->counter_id == $val->id)
                          <?php $print = false?>
                          @if($val->current_queue == 0)
                            {{'---'}}
                          @else
                            {{sprintf("%03d", $data->current_queue)}}
                          @endif
                          <?php break;?>
                        @endif
                      @endforeach
                    @endif
                    @if($print == true)
                    {{'---'}}
                    @endif
                  </h2>
                </div>
              </div>
          @endforeach
          </div>
        </div>

    </div>
    <iframe src="{{asset('sound/250ms-silence.mp3')}}" allow="autoplay" id="audio" style="display: none"></iframe>
    <audio id="audio_nomor_antrian" src="{{asset('sound/antrian_berikutnya.wav')}}" allow=”autoplay” mute='muted'></audio>
</body>
</html>

@section('javascript')
<script>
var index = 1;

$(document).ready(function(){
  setInterval(function(){getdata();}, 5000);
  // getdata();
});

function play(){
  var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
  if (!isChrome){
    $('#audio_nomor_antrian').get(0).play();
  }
  else {
    $('#audio_nomor_antrian').get(0).play();
  };
  
}
function getdata(){
  $.ajax({
    url: "{{route('queueinfo.check')}}",
    method: "POST",
    data:{_token:"{{csrf_token()}}"},
    success:function(data){
      console.log(data);
      var index = 1;
      var data_index = 0;
      for (let i = 0; i < data.result.length; i++) {
        let no = ('00'+ data.result[i].current_queue).slice(-3);
        let no_antri = no;
        let no_skrg = $("#antrian_"+data.result[i].counter_id+'-'+data.result[i].counter_type).text();
        let trim = no_skrg.trim();  
        if(trim != no_antri){
          if(data.result[i].current_queue == 0){

          }else{
            play();
            $("#antrian_"+data.result[i].counter_id+'-'+data.result[i].counter_type).text("");
            $("#antrian_"+data.result[i].counter_id+'-'+data.result[i].counter_type).text(no_antri);
          }
        }
          
      }
    }
  });
  index = 1;
}
</script>
@endsection