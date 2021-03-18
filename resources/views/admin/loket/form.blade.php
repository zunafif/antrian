<form action="{{route('counter_master.store')}}" method="POST">
  {{csrf_field()}}
  <input type="hidden" name="id" value="{{isset($counter->id)? $counter->id:''}}">
  <div class="form-group">
    <label for="code_alpha">Kode</label>
    <input type="text" maxlength="2" class="form-control col-md-3" id="code_alpha" placeholder="Contoh: RJ" name="code_alpha" value="{{isset($counter->code_alpha)? $counter->code_alpha:''}}">
  </div>
  <div class="form-group">
    <label for="name">Nama</label>
    <input type="text" class="form-control col-md-4" id="name" placeholder="Contoh: Rawat Jalan" name="name" value="{{isset($counter->name)? $counter->name:''}}">
  </div>
  <div class="form-group">
    <label for="type">Tipe</label>
    <!-- <input type="text" class="form-control" id="type" placeholder="Contoh: Rawat Jalan"> -->
    <select name="counter_type" id="type" class="form-control col-md-2">
      <option value="" default disabled selected>Pilih Tipe Loket</option>
      <option value="1" {{isset($counter->counter_type)? ($counter->counter_type == 1)? 'selected':'' :''}}>Khusus</option>
      <option value="2" {{isset($counter->counter_type)? ($counter->counter_type == 2)? 'selected':'' :''}}>Umum</option>
    </select>
  </div>
  <div class="form-group">
    <label for="">Status</label>
    <br>
    <input type="radio" name="status" value="1" checked> Aktif 
    <br>
    <input type="radio" name="status" value="0"> Tidak Aktif
  </div>
  <div class="from-group pull-right">
    <a href="{{route('counter_master.index')}}"><div class="btn">Batal</div></a>
    <input type="submit" value="Simpan" class="btn btn-success">
  </div>
</form>