<!--Hey! This is the original version
of Simple CSS Waves-->
<link rel="icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
<title>{{config('app.name')}}</title>
<style>
@import url(//fonts.googleapis.com/css?family=Lato:300:400);

body {
  margin:0;
}

h1 {
  font-family: 'Lato', sans-serif;
  font-weight:300;
  letter-spacing: 2px;
  font-size:48px;
}
p {
  font-family: 'Lato', sans-serif;
  letter-spacing: 1px;
  font-size:14px;
  color: #333333;
}

.header {
  position:relative;
  text-align:center;
  background: linear-gradient(60deg, rgba(84,58,183,1) 0%, rgba(0,172,193,1) 100%);
  color:white;
}
.logo {
  width:50px;
  fill:white;
  padding-right:15px;
  display:inline-block;
  vertical-align: middle;
}

.inner-header {
  height:65vh;
  width:100%;
  margin: 0;
  padding: 0;
}

.flex { /*Flexbox for containers*/
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.waves {
  position:relative;
  width: 100%;
  height:15vh;
  margin-bottom:-7px; /*Fix for safari gap*/
  min-height:100px;
  max-height:150px;
}

.content {
  position:relative;
  height:20vh;
  text-align:center;
  background-color: white;
}

/* Animation */

.parallax > use {
  animation: move-forever 25s cubic-bezier(.55,.5,.45,.5)     infinite;
}
.parallax > use:nth-child(1) {
  animation-delay: -2s;
  animation-duration: 7s;
}
.parallax > use:nth-child(2) {
  animation-delay: -3s;
  animation-duration: 10s;
}
.parallax > use:nth-child(3) {
  animation-delay: -4s;
  animation-duration: 13s;
}
.parallax > use:nth-child(4) {
  animation-delay: -5s;
  animation-duration: 20s;
}
@keyframes move-forever {
  0% {
   transform: translate3d(-90px,0,0);
  }
  100% { 
    transform: translate3d(85px,0,0);
  }
}

/* custom */
.row{
  width:100%;
  clear:both;
}
.btn{
  background-color:unset;
  border:2px solid white;
  border-radius:15px;
  color:white;
  font-size:16px;
  padding:5px 20px 7px 20px;
  margin:0px 3px 0px 3px;
}
.btn-full{
  background-color:white;
  color:grey !important
}
.btn-success{
  background-color:#28a745;
  border:none;
}
.form{
  padding-left:30%;
  padding-right:30%;

}
.form-header{
  font-family:helvetica;
  margin-bottom:15px;
  font-size:18px;
}
.form-control{
  width:100%;
  font-size:14px;
  padding:5px 10px 5px 10px;
  margin-bottom:7px;
  border:unset;
  border-radius:5px;
}
/* end of custom */

/*Shrinking for mobile*/
@media (max-width: 768px) {
  .waves {
    height:40px;
    min-height:40px;
  }
  .content {
    height:30vh;
  }
  h1 {
    font-size:24px;
  }
}
</style>
<div class="header">

<!--Content before waves-->
<div class="inner-header flex">
<!--Just the logo.. Don't mind this-->
  <div class="row">
    <img src="{{asset('img/rsbunda.png')}}" alt="" height="100px" width="120px" style="margin-right:15px;">
    <h1>Antrian RS BUNDA</h1>
  </div>
  @guest
  <div class="row landing-button">
    <button class="btn login-button">Login</button>
  </div>
  @endguest
  @auth
  <div class="row landing-button">
    <a href="{{route('dashboard.index')}}"><button class="btn btn-full">Dashboard</button></a>
    <a href="{{route('queueinfo.read')}}"><button class="btn btn-full">Antrian</button></a>
  </div>
  @endauth
  <div class="row landing-form" style="display:none;">
    <div class="form">
    <form method="POST" action="{{ route('login') }}">
            @csrf
        <div class="form-header">
          Admin
        </div>
        <div class="form-body">
          <input type="email" name="email" :value="old('email')" placeholder="Username" class="form-control">
          <input type="password" name="password" required autocomplete="current-password" placeholder="Password" class="form-control">
        </div>
        <div class="form-footer">
          <input type="submit" value="Login" class="btn btn-success">
          <input type="button" value="Batal" class="btn btn-default cancel-login">
        </div>
      </form>
    </div>
    
  </div>
</div>
<!--Waves Container-->
<div>
<svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
<defs>
<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
</defs>
<g class="parallax">
<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
<use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
</g>
</svg>
</div>
<!--Waves end-->

</div>
<!--Header ends-->

<!--Content starts-->
<div class="content flex">
<p>IT RS Bunda</p>
<!-- <p>Daniel Österman | 2019 | Free to use</p> -->
</div>
<!--Content ends-->
<script src="{{asset('admin/assets/js/core/jquery.min.js')}}"></script>
<script>
$(document).ready(function(){
  $('.landing-button').show();
  $('.landing-form').hide();
})

$('.login-button').on('click',function(){
  $('.landing-button').hide();
  $('.landing-form').show();
})

$('.cancel-login').on('click',function(){
  $('.landing-button').show();
  $('.landing-form').hide();
})
</script>