<form action="{{route('sound_master.store')}}" method="POST">
  {{csrf_field()}}
  <input type="hidden" name="id" value="{{isset($user->id)? $user->id:''}}">
  <div class="form-group">
    <label for="name">Nama</label>
    <input type="text" class="form-control col-md-3" id="name" placeholder="Contoh: Bunda" name="name" value="{{isset($user->name)? $user->name:''}}" {{isset($user->name)? 'readonly':''}}>
  </div>
  <div class="form-group">
    <label for="email">Deskripsi</label>
    <textarea name="description" id="" cols="30" rows="10"></textarea>
    <input type="email" class="form-control col-md-4" id="email" placeholder="Contoh: rsbunda@gmail.com" name="email" value="{{isset($user->email)? $user->email:''}}" {{isset($user->name)? 'readonly':''}}>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" placeholder="******" name="password" class="form-control col-md-4">
    <span class="" style="font-size:12;color:red;">*Jika dikosongi password default anda: {{Config::get('antrian.default_password')}}</span>
  </div>
  <div class="form-group">
    <label for="role">Role</label>
    <select name="role" id="role" class="form-control col-md-4">
      @foreach($role as $data)
      <option value="{{$data->name}}" {{($data->name === $role_user)? 'selected':''}}>{{$data->name}}</option>
      @endforeach
    </select>
  </div>
  <div class="from-group pull-right">
    <a href="{{route('user_master.index')}}"><div class="btn">Batal</div></a>
    <input type="submit" value="Simpan" class="btn btn-success">
  </div>
</form>