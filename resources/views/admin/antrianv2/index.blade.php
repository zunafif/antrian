@extends('admin/layout/master_blank')
<style>
      .address{
        font-size:16px;
      }
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
    <link rel="stylesheet" href="{{asset('css/plugins/font-awesome.min.css')}}">
    <title>{{config('app.name')}}</title>
</head>
<body>
<nav class="navbar navbar-light white justify-content-between">
<a class="navbar-brand waves-effect" href="#">
          <img src="{{asset('img/rsbunda.png')}}" style="width:65px; height:60px;" alt="Logo">
        </a><h3 class="text-center"><b>{{Config::get('antrian.nama_rs')}}</b><br><span class="address">{{Config::get('antrian.alamat_rs')}}</span></h3>
        
  <form class="form-inline my-1">
  <a href="{{route('index')}}" style="color:black"><b><h4 style="letter-spacing:1px;" class="clock"></h4></b></a>
  <!-- <a href="{{url('/')}}" class="btn btn-teal"><i class="fa fa-home fa-2x"></i></a> -->
  <h5></h5>
  </form>
</nav>

    <div class="col-12 ml-auto mt-4" style="padding-right:30px;padding-left:30px;">
        <div class="row">
          <div style="width:100%;text-align:center;font-size:30px;font-weight:bold;color:white;letter-spacing:1px;">ANTRIAN</div>
          <div style="position:absolute;right:25" class="sound-button"><i id="sound-icon" class="fa fa-volume-off fa-2x" style="color:white"></i> </div>
          <div style="width:100%;padding-top:15px;">
          <?php $index = 0;?>
          
          @foreach($counter as $val)    
              <div style="width:{{100/Config('antrian.queue_column')}}%;display:block;padding:25px 10px;float:left;">
                <div style="background-color:white;padding:55px 7px;text-align:center;border-radius:10px;">
                  <h3 style="font-size:35px;margin-bottom:50px;">
                    <b>
		    Loket {{$val->code_alpha}}
		    </b>
		    <br/>
                    <!-- {{$val->name}} -->
                    @if($val->counter_type == 2)
                      {{Config::get('antrian.general_counter_name')}}
                    @endif
                    
                  </h3>
                  <audio id="audio_counter-{{$val->counter_type.'-'.$val->id}}" src="{{asset($val->path)}}" allow=”autoplay” mute='muted'></audio>
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
                            {{$data->current_code_alpha}}{{sprintf("%03d", $data->current_queue)}}
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
    <audio id="audio_nomor_antrian" src="{{asset('sound/antrian.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="ke" src="{{asset('sound/ke.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="loket" src="{{asset('sound/loket.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_alpha" src="{{asset('sound/500ms-silent.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_1" src="{{asset('sound/500ms-silent.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_2" src="{{asset('sound/500ms-silent.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_3" src="{{asset('sound/500ms-silent.wav')}}" allow=”autoplay” mute='muted'></audio>
    <!-- <audio id="audio_0" src="{{asset('sound/0.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_1" src="{{asset('sound/1.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_2" src="{{asset('sound/2.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_3" src="{{asset('sound/3.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_4" src="{{asset('sound/4.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_4" src="{{asset('sound/4.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_5" src="{{asset('sound/5.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_6" src="{{asset('sound/6.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_7" src="{{asset('sound/7.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_8" src="{{asset('sound/8.wav')}}" allow=”autoplay” mute='muted'></audio>
    <audio id="audio_9" src="{{asset('sound/9.wav')}}" allow=”autoplay” mute='muted'></audio> -->
</body>
</html>

@section('javascript')
<script>
var index = 1;

$(document).ready(function(){
  setInterval(function(){getdata();}, 5000);
  // getdata();
  
  getdate();
});

function getdate(){
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
    if(m<10){
        m = "0"+m;
    }
    if(s<10){
        s = "0"+s;
    }
  $(".clock").text(h+":"+m+":"+s);
  setTimeout(function(){getdate()}, 500);
}


// function play(){
//   var audio = document.getElementById("audio_nomor_antrian");
//   audio.play();
// }
function play(counter_type,counter_id,queue,code_alpha){  
  var audio = document.getElementById("audio_nomor_antrian");
  audio.play();

  var audio_no;
  var nextAudio;
  queue = queue + '';
  var arque = queue.split('');
  // var audio_no = document.getElementById('audio');
  // audio.onended = function(){
  //   audio_no.play();
  // }
  // var duration = 0;
  // audio_no.onended = function(){
  //   for (let i = 0; i < arque.length; i++) {
  //     nextSong = "sound/"+arque[i]+".wav";
  //     audio_no.src = nextSong;
  //     audio_no.load();
  //     audio_no.play();
  //   }
  // }
  
  var audio_alpha = document.getElementById('audio_alpha');
  audio_alpha.src = "sound/"+code_alpha+".wav";
  audio_alpha.load();
  audio.onended = function(){
    audio_alpha.play();
  }

  var audio_no = document.getElementById('audio_1');
  audio_alpha.onended = function(){
    audio_no.play();
  }
  var audio_no2 = document.getElementById('audio_2');
  var audio_no3 = document.getElementById('audio_3');
  if(arque.length == 1){
    document.getElementById("audio_1").muted = false;
    audio_no.src = "sound/"+arque[0]+".wav";
    audio_no.load();
    audio_alpha.onended = function(){
      audio_no.play();
    }
    document.getElementById("audio_2").muted = true;
    document.getElementById("audio_3").muted = true;
  }
  if(arque.length == 2){
    document.getElementById("audio_1").muted = false;
    document.getElementById("audio_2").muted = false;
    audio_no.src = "sound/"+arque[0]+".wav";
    audio_no.load();
    audio_alpha.onended = function(){
      audio_no.play();
    }

    audio_no2.src = "sound/"+arque[1]+".wav";
    audio_no2.load();
    audio_no.onended = function(){
      audio_no2.play();
    }
    document.getElementById("audio_3").muted = true;
  }
  if(arque.length == 3){
    document.getElementById("audio_1").muted = false;
    document.getElementById("audio_2").muted = false;
    document.getElementById("audio_3").muted = false;
    audio_no.src = "sound/"+arque[0]+".wav";
    audio_no.load();
    audio_alpha.onended = function(){
      audio_no.play();
    }
    audio_no2.src = "sound/"+arque[1]+".wav";
    audio_no2.load();
    audio_no.onended = function(){
      audio_no2.play();
    }
    audio_no3.src = "sound/"+arque[2]+".wav";
    audio_no3.load();
    audio_no2.onended = function(){
      audio_no3.play();
    }
  }
  
  var loket = document.getElementById("loket");
  setTimeout(function(){
  // audio_no.onended = function(){
    loket.play();
  // }
  },(arque.length*1000)+2000)
    
  

  var audio_counter = document.getElementById("audio_counter-"+counter_type+"-"+counter_id);
  loket.onended = function() {
    audio_counter.play();
  }
}

function getdata(){
  $.ajax({
    url: "{{route('queueinfov2.check')}}",
    method: "POST",
    data:{_token:"{{csrf_token()}}"},
    success: function(data){
      var index = 1;
      var data_index = 0;
      for (let i = 0; i < data.result.length; i++) {
        let no = ('00'+ data.result[i].current_queue).slice(-3);
        let no_antri = data.result[i].current_code_alpha + no;
        let no_skrg = $("#antrian_"+data.result[i].counter_id+'-'+data.result[i].counter_type).text();
        let no_alpha = (data.result[i].current_code_alpha != null)? data.result[i].current_code_alpha : "";
        let trim = no_skrg.trim();
        if(trim != no_antri){
          if(data.result[i].current_queue == 0){
            
          }else{
            // play()
            // playCounter(data.result[i].counter_type,data.result[i].counter_id)
            play(data.result[i].counter_type,data.result[i].counter_id,data.result[i].current_queue,data.result[i].current_code_alpha)
              
            console.log("#antrian_"+data.result[i].counter_id+'-'+data.result[i].counter_type);
            console.log(no_antri);
            $("#antrian_"+data.result[i].counter_id+'-'+data.result[i].counter_type).text("");
            $("#antrian_"+data.result[i].counter_id+'-'+data.result[i].counter_type).text(no_antri);
          }
        }
          
      }
    }
  });
  index = 1;
}

$(".sound-button").on('click', function(){
  if($("#sound-icon").hasClass('fa-volume-up')){
    $("#sound-icon").removeClass('fa-volume-up');
    $("#sound-icon").addClass('fa-volume-off');
    var aud = document.getElementById("audio_nomor_antrian");
    aud.muted = true;
  }else{
    $("#sound-icon").removeClass('fa-volume-off');
    $("#sound-icon").addClass('fa-volume-up');
    var aud = document.getElementById("audio_nomor_antrian");
    aud.muted = false;
  }
  
  
})
</script>
@endsection
