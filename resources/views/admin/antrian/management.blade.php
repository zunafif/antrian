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
          </table>
        </div>
      </div>
    </div>
    <div class="row" style="margin-left:0px;margin-right:0px;">
      <i class="material-icons text-danger">filter_alt</i>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <select name="" id="" class="form-control">
          <option value="" default disable>Pilih Loket Terlebih Dahulu</option>
          <option value="" >RJ</option>
          <option value="" >RI</option>
          <option value="" >Loket 1</option>
          <option value="" >Loket 2</option>
          <option value="" >Loket 3</option>
        </select>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-1">
        <input type="button" class="btn btn-success" value="filter">
      </div>
    </div>
  </div>
</div>
@endsection