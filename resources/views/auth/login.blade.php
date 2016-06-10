@extends('layout.app')

@section('title')
  Login | Quiz
@endsection

@section('content')
  <section class="section">
    <div class="container">
      <br><br>
      <div class="row">
          <h5 class="blue-text col-md-6 col-md-offset-3">Login</h5>
        @if (count($errors) > 0)
          <div class="alert alert-danger col offset-m2 s12 m8">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        </div>

      <div>
        <form class="col-md-6 col-md-offset-3" role="form" method="POST" action="{{ url('/auth/login') }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="row">
            <div class="input-field">
              <div class="col-md-6">
                <label for="email">Email</label>
              </div>
              <div class="col-md-6">
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="input-field">
              <div class="col-md-6">
                <label for="password">Password</label>
              </div>
              <div class="col-md-6">
                <input id="password" type="password" name="password" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <button class="btn waves-effect waves-light btn-large col offset-l4 l4 m12" type="submit" name="action">Submit</button>
          </div>
        </form>
      </div>
      <br><br>
    </div>
  </section>
@endsection