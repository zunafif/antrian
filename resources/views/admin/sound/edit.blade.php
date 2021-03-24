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
        <h3 class="card-title">Edit Suara</h3>
      </div>
      <div class="card-body">
        @include('admin.user.form')
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="disable-button" value="0">
@endsection

@section('javascript')

@endsection
<div id="dialog-confirm" title="Peringatan !" style="display:none">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Apakah anda yakin untuk melewati antrian selanjutnya?</p>
</div>
</body>

</html>