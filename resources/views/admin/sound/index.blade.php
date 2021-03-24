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
@if (flash()->message)
    <div class="alert {{ flash()->class }}">
        {{ flash()->message }}
    </div>
@endif
  <div class="container-fluid">
    <div class="card">
      <div class="card-header card-header-primary" style="background: linear-gradient(60deg, #00B59D, #00584D);">
        <h3 class="card-title">Master Suara</h3>
      </div>
      <div class="card-body">
        <div id="typography">
          <div class="card-title">
            <a href="{{route('user_master.create')}}">
              <button type="button" class="btn btn-success pull-right"><i class="material-icons">add</i> Tambah</button>
            </a>
          </div>
          <table class="table table-stripped">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th>Path</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($user as $i => $dat)
                <tr>
                  <td>{{$i+1}}</td>
                  <td>{{$dat->name}}</td>
                  <td>{{$dat->email}}</td>
                  <td>
                    @foreach($dat->getRoleNames() as $role)
                    {{$role}}
                    @endforeach
                  </td>
                  <td class="text-center">
                    <a href="{{route('user_master.edit',['id' => $dat->id])}}"><button class="btn btn-info"><i class="material-icons">edit</i> Edit</button></a>
                    <div class="btn btn-danger delete-button" data-id="{{$dat->id}}"><i class="material-icons">delete</i> Delete</div> 
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="disable-button" value="0">
@endsection

@section('javascript')
<script>
$(".alert").delay(1500).fadeOut(200, function() {
    $(this).alert('close');
});
$('.delete-button').on('click',function(){
  var id = $(this).data('id');
  Swal.fire({
    title: 'Perhatian',
    text: 'Apakah anda yakin untuk menghapus?',
    icon: 'warning',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    $.ajax({
      method: 'POST',
      url: "{{route('counter_master.delete')}}",
      data: {_token: "{{ csrf_token() }}", id: id},
      success: function (data){
        if(data == 1){
          Swal.fire(
            'Terhapus!',
            'Data berhasil dihapus.',
            'success'
          )
          location.reload();
        }else{
          Swal.fire(
            'Gagal!',
            'Data tidak berhasil dihapus.',
            'error'
          )
        }
      }
    })
  })
})
</script>
@endsection
</body>

</html>