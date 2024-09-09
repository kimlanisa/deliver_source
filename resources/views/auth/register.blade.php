@extends('layouts.simple',[
  'title' => 'Register',
])

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css')}}">
    @endsection
    
@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/master/kader.js') }}"></script>
    <script src="{{ asset('js/oneui.app.js') }}"></script>
    

     <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.js')}}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

    <script>One.helpersOnLoad(['js-flatpickr']);</script>
    
    <script>
      $(document).ready(function (e) { 
        $('#btn-save').prop( "disabled", true );

        //btn enabled when check
        $('#check1').change(function(){
          if (($('#check1').is(':checked')) && ($('#check2').is(':checked'))){
            $('#btn-save').prop( "disabled", false );
          }
        });

        $('#check2').change(function(){
          if (($('#check1').is(':checked')) && ($('#check2').is(':checked'))){
            $('#btn-save').prop( "disabled", false );
          }
        });

        //btn disabled when uncheck
        $('#check2').change(function(){
          if ((!$('#check2').is(':checked'))){
            $('#btn-save').prop( "disabled", true );
          }
        });

        $('#check1').change(function(){
          if ((!$('#check1').is(':checked'))){
            $('#btn-save').prop( "disabled", true );
          }
        });
        
        //foto
        $('#foto_profile').change(function(){     
          let reader = new FileReader();
          reader.onload = (e) => { 
            $('#preview-foto_profile-before-upload').attr('src', e.target.result); 
          }
          reader.readAsDataURL(this.files[0]); 
        });

        $('#foto_ktp').change(function(){     
          let reader = new FileReader();
          reader.onload = (e) => { 
            $('#preview-foto_ktp-before-upload').attr('src', e.target.result); 
          }
          reader.readAsDataURL(this.files[0]); 
        });
        //foto
      });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
    </script>

    <script>
      //alamat
      $('#provinsi_id').change(function(){
        $("#kota_id").empty();
        $("#kecamatan_id").empty();
        var provinsi_id = $(this).val();  
        console.log(provinsi_id);  
        if(provinsi_id){
            $.ajax({
            type:"GET",
            url:"{{route('getKota')}}",
            data : {
                provinsi_id : provinsi_id
            },
            success:function(res){               
                if(res){
                    $("#kota_id").empty();
                    $("#kota_id").append('<option value="">---pilih kabupaten/kota---</option>');
                    $.each(res,function(index,kota){
                        $("#kota_id").append('<option value="'+kota.id+'">'+kota.kota+'</option>');
                    });
                }else{
                $("#kota_id").empty();
                }
            }
            });
            }else{
            
            }      
      });

        $('#kota_id').change(function(){
            $("#kecamatan_id").empty();
            var kota_id = $(this).val();  
            console.log(kota_id);  
            if(kota_id){
                $.ajax({
                type:"GET",
                url:"{{route('getKecamatan')}}",
                data : {
                    kota_id : kota_id
                },
                success:function(res){               
                    if(res){
                        $("#kecamatan_id").empty();
                        $("#kecamatan_id").append('<option value="">---pilih kecamatan---</option>');
                        $.each(res,function(index,kecamatan){
                            $("#kecamatan_id").append('<option value="'+kecamatan.id+'">'+kecamatan.kecamatan+'</option>');
                        });
                    }else{
                    $("#kecamatan_id").empty();
                    }
                }
                });
                }else{
                
                }      
            });
            //alamat
    </script>


@endsection

@section('content')
<main id="main-container">

        <!-- Page Content -->
        <div class="content">
          <!-- Flatpickr Datetimepicker (.js-flatpickr class is initialized in Helpers.jsFlatpickr()) -->
          <!-- For more info and examples you can check out https://github.com/flatpickr/flatpickr -->
          <div class="block block-rounded">
          <form action="{{route('registerMember')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="content content-boxed">
              <div class="block-header block-header-default">
                <h3 class="block-title">1. Register Form</h3>
              </div>
                <div class="row mt-4">
                  <div class="col-lg-5">
                    <p class="fs-sm text-muted">
                        Informasi personal dari kader untuk identifikasi
                    </p>
                    <p class="fs-sm text-muted">
                      <b>
                        Harap perhatikan dengan seksama terhadap data yang anda masukkan, karena data-data ini bersifat rahasia dan harus bisa dipertanggung jawabkan kemudian hari
                      </b>                    
                    </p>
                    <img  src="{{asset('/media/avatars/register.png')}}" alt="preview image" style="max-height: 200px;">
                  </div>
                  <div class="col-lg-7 col-xl-7" style="padding-left:30px;">
                    <div class="row mb-4">
                      <div class="col-xl-12">
                        <label class="form-label" for="nama lengkap">Nama Lengkap</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror form-control form-control-lg" name="name" value="{{ old('name') }}" autocomplete="off" placeholder="Nama lengkap"  required autocomplete="off">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="row mb-4">
                      <div class="col-xl-6">
                        <label class="form-label" for="jenis-kelamin">Jenis Kelamin</label>
                        <select required type="text" class="form-select" name="jenis_kelamin" id="jenis_kelamin">
                            <option value="Laki-laki" {{(old('jenis_kelamin')=='Laki-laki')? 'selected':''}}>Laki-laki</option>
                            <option value="Perempuan" {{(old('jenis_kelamin')=='Perempuan')? 'selected':''}}>Perempuan</option>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-label" for="tempat-lahir">Tempat Lahir</label>
                        <input required type="text" class="form-control" value="{{ old('tempat_lahir') }}" id="tempat_lahir" name="tempat_lahir" autocomplete="off" placeholder="tempat lahir"/>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <div class="col-xl-6">
                        <label class="form-label" for="example-flatpickr-default">Tanggal Lahir</label>
                        <input required type="text" class="js-flatpickr form-control" value="{{ old('tgl_lahir') }}"  id="tgl_lahir" name="tgl_lahir" autocomplete="off" placeholder="Y-m-d">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-label" for="gol-darah">Golongan Darah</label>
                        <select class="form-select" type="text" name="golongan_darah" autocomplete="off" id="golongan_darah">
                            <option value="-">--</option>
                            <option value="A" {{(old('golongan_darah')=='A')? 'selected':''}}>A</option>
                            <option value="B" {{(old('golongan_darah')=='B')? 'selected':''}}>B</option>
                            <option value="AB" {{(old('golongan_darah')=='AB')? 'selected':''}}>AB</option>
                            <option value="O" {{(old('golongan_darah')=='O')? 'selected':''}}>O</option>
                        </select>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <div class="col-xl-6">
                        <label class="form-label" for="gol-darah">Status Perkawinan</label>
                        <select required class="form-select" type="text" name="status_kawin" id="status_kawin" autocomplete="off">
                            <option value="-">--</option>
                            <option value="BELUM KAWIN" {{(old('status_kawin')=='BELUM KAWIN')? 'selected':''}}>BELUM KAWIN</option>
                            <option value="SUDAH KAWIN" {{(old('status_kawin')=='SUDAH KAWIN')? 'selected':''}}>SUDAH KAWIN</option>
                            <option value="PERNAH KAWIN" {{(old('status_kawin')=='PERNAH KAWIN')? 'selected':''}}>PERNAH KAWIN</option>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-label" for="gol-darah">Status Pekerjaan</label>
                        <select class="form-select" type="text" name="status_kerja" id="status_kerja" autocomplete="off">
                            <option value="">--</option>
                            @foreach ($pekerjaan as $kerja)
                            <option value="{{$kerja->id}}" {{(old('status_kerja')==$kerja->id)? 'selected':''}}>{{$kerja->pekerjaan}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <div class="col-xl-6">
                        <label class="form-label" for="gol-darah">Agama</label>
                        <select required  class="form-select" type="text" name="agama" id="agama" autocomplete="off">
                            <option value="-">--</option>
                            <option value="Islam" {{(old('agama')=='Islam')? 'selected':''}}>Islam</option>
                            <option value="Kristen" {{(old('agama')=='Kristen')? 'selected':''}}>Kristen</option>
                            <option value="Katholik" {{(old('agama')=='Katholik')? 'selected':''}}>Katholik</option>
                            <option value="Hindu" {{(old('agama')=='Hindu')? 'selected':''}}>Hindu</option>
                            <option value="Budha" {{(old('agama')=='Budha')? 'selected':''}}>Budha</option>
                            <option value="Kong Hu Chu" {{(old('agama')=='Kong Hu Chu')? 'selected':''}}>Kong Hu Chu</option>
                            <option value="Aliran Kepercayaan" {{(old('agama')=='Aliran Kepercayaan')? 'selected':''}}>Aliran Kepercayaan</option>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-label" for="gol-darah">No KTP</label>
                        <input required type="number" class="form-control" id="no_ktp" name="no_ktp" autocomplete="off" placeholder="no ktp"/>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <div class="col-xl-6">
                        <label class="form-label" for="gol-darah">No Telepon</label>
                        <input type="number" class="form-control" value="{{ old('no_telp') }}" autocomplete="off" id="no_telp" name="no_telp" placeholder="no telepon"/>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-label" for="sayap">Pendidikan Terakhir</label>
                        <select class="form-select" type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" autocomplete="off">
                            <option value="-">--</option>
                            @foreach ($pendidikan as $pdd)
                              <option value="{{$pdd->pendidikan}}" {{(old('pendidikan_terakhir')==$pdd->pendidikan)? 'selected':''}}>{{$pdd->pendidikan}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <div class="col-xl-12">
                        <label class="form-label" for="sayap">Sayap</label>
                        <select class="form-select" type="text" name="sayap" id="sayap" autocomplete="off">
                            <option value="-">--</option>
                            <option value="Garda Pemuda Nasdem" {{(old('sayap')=='Garda Pemuda Nasdem')? 'selected':''}}>Garda Pemuda Nasdem</option>
                            <option value="Garnita Malajayati NasDem" {{(old('sayap')=='Garnita Malajayati NasDem')? 'selected':''}}>Garnita Malajayati NasDem</option>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-4">
                      <div class="col-xl-12">
                        <label class="form-label" for="ref referal">Ref Referal Code</label>
                        <input type="text" class="form-control" value="{{ old('ref_referal_code') }}" name="ref_referal_code" id="ref_referal_code" autocomplete="off" placeholder="referensi referal code..">
                      </div>
                    </div>
                  </div>
                  
                </div>
                
                <div class="block block-rounded mt-4">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">2. User Email & Password Login</h3>
                  </div>
                  <div class="block-content">
                      <div class="row">
                        <div class="col-lg-5">
                          <p class="fs-sm text-muted">
                            Akun untuk masuk ke dalam sistem dan Informasi data singkat diperlukan untuk pengiriman info dan berita
                          </p>
                          
                        </div>
                        
                        <div class="col-lg-7 col-xl-7" style="padding-left:30px;">
                          <div class="row mb-4">
                            <div class="col-xl-12">
                              <label class="form-label" for="nama lengkap">User Email</label>
                              <input required type="email" class="form-control" value="{{ old('email') }}" id="email" name="email" autocomplete="off" placeholder="User email">
                            </div>
                          </div>

                          <div class="row mb-4">
                            <div class="col-xl-12">
                              <label class="form-label" for="nama lengkap">Password</label>
                              <input required type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" name="password" id="password" required autocomplete="new-password" placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          </div>
                          <div class="row mb-4">
                            <div class="col-xl-12">
                              <label class="form-label" for="nama lengkap">Confirm Password</label>
                              <input type="password" class="form-control form-control-lg form-control-alt" id="password-confirm" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            </div>
                          </div>
                        
                        </div>
                      </div>
                  </div>
                </div>

                <div class="block block-rounded mt-4">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">3. Alamat</h3>
                  </div>
                  <div class="block-content">
                      <div class="row">
                        <div class="col-lg-5">
                          <p class="fs-sm text-muted">
                            Informasi data singkat diperlukan untuk identifikasi
                          </p>
                          <img  src="{{asset('/media/avatars/address.png')}}" alt="preview image" style="max-height: 200px;">
                        </div>
                        <div class="col-lg-7 col-xl-7" style="padding-left:30px;">
                          <input hidden id="negara" name="negara" value="Indonesia" type="text">
                          <div class="row mb-4">
                            <div class="col-xl-6">
                              <label class="form-label" for="provinsi_id">Provinsi</label>
                              <select type="text" class="form-select" name="provinsi_id" id="provinsi_id">
                                  <option value="">-</option>
                                  @foreach ($provinsi as $prov)
                                  <option value="{{$prov->id}}" {{(old('provinsi_id')==$prov->id)? 'selected':''}}>{{$prov->provinsi}}</option>
                                  @endforeach
                              </select>
                            </div>
                            <div class="col-xl-6">
                              <label class="form-label" for="kota_id">Kabupaten/Kota</label>
                              <select type="text" class="form-select" name="kota_id" id="kota_id">
                                  <option value="">-</option>
                              </select>
                            </div>
                          </div>
                          <div class="row mb-4">
                            <div class="col-xl-6">
                              <label class="form-label" for="kecamatan_id">Kecamatan</label>
                              <select type="text" class="form-select" name="kecamatan_id" id="kecamatan_id">
                                  <option value="">-</option>
                              </select>
                            </div>
                            <div class="col-xl-6">
                              <label class="form-label" for="kelurahan">Kelurahan</label>
                              <input type="text" class="form-control" value="{{ old('desa') }}" id="desa" name="desa" autocomplete="off" placeholder=""/>
                            </div>
                          </div>
                          <div class="row mb-4">
                            <div class="col-xl-6">
                              <label class="form-label" for="rt">RT</label>
                              <input type="number" class="form-control" value="{{ old('rt') }}" id="rt" name="rt" autocomplete="off" placeholder=""/>
                            </div>
                            <div class="col-xl-6">
                              <label class="form-label" for="rw">RW</label>
                              <input type="number" class="form-control" value="{{ old('rw') }}" id="rw" name="rw" autocomplete="off" placeholder=""/>
                            </div>
                          </div>
                          <div class="row mb-4">
                            <div class="col-xl-12">
                              <label class="form-label" for="alamat">Alamat</label>
                              <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="4">{{old('alamat')}}</textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>

                <div class="block block-rounded">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">4. Dokumen Pendukung</h3>
                  </div>
                  <div class="block-content block-content-full">
                    <div class="row">
                      <div class="col-lg-5">
                          <p class="fs-sm text-muted">
                            Untuk memverifikasi identitas anda, silakan unggah dokumen anda.
                          </p>
                          <p>
                            <b>
                              Untuk mempercepat verifikasi data, mohon dipastikan dokumen yang diunggah harus sesuai ketentuan, sehingga mudah dibaca sistem :
                            </b>
                          </p>
                          <ul class="fs-sm text-muted">
                            <li>Foto diri : berwarna, setengah badan, bukan crop KTP, dan jelas (tidak buram).</li>
                            <li>KTP : Foto tegak dari atas, usahakan ditempat terang tanpa blitz, posisi mendatar, tidak diperkenankan fotocopy hitam/putih.</li>
                          </ul>
                      </div>
                        
                      <div class="col-lg-7 col-xl-7" style="padding-left:30px;">
                        <div class="form-group">
                          <p><b>Unggah Foto Anda Disini!</b></p>
                            <input type="file" name="foto_profile" placeholder="Choose image" id="foto_profile">
                            @error('image')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-2 mt-4">
                          <img id="preview-foto_profile-before-upload" src="{{asset('/media/avatars/upload.jpg')}}"
                              alt="preview image" style="max-height: 200px;">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-5">
                          
                      </div>
                        
                      <div class="col-lg-7 col-xl-7" style="padding-left:30px;">
                        <div class="form-group">
                          <hr>
                          <p><b>Unggah Foto KTP!</b></p>
                            <input type="file" name="foto_ktp" placeholder="Choose image" id="foto_ktp">
                            @error('image')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-2 mt-4">
                          <img id="preview-foto_ktp-before-upload" src="{{asset('/media/avatars/upload.jpg')}}"
                              alt="preview image" style="max-height: 200px;">
                        </div>
                        <hr>
                        <div class="alert alert-info " role="alert">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="check1" name="example-checkbox-default1">
                            <label class="form-check-label" for="example-checkbox-default1">Saya menyatakan setuju dan sudah membaca Syarat dan Ketentuan menjadi Kader/Anggota Partai</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="check2" name="example-checkbox-default1">
                            <label class="form-check-label" for="example-checkbox-default1">Saya menyatakan bahwa semua informasi yang saya berikan adalah benar.</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row mb-4 mt-4">
                      <div class="col-lg-5">
                        
                      </div>
                      <div class="col-lg-7 col-xl-7" style="padding-left:30px;">
                        <button id="btn-save" type="submit" class="btn btn-primary">Register</button>
                        <a href="/" id="btn-login" type="button" class="btn btn-warning">Login</a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          </form> 
        </div>
        <!-- END Page Content -->
      </main>
    
@endsection
