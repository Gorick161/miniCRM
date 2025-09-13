{{-- Component wrapper that uses the new app_pro shell and passes the slot --}}
@extends('layouts.app_pro')

@section('content')
  {{ $slot }}
@endsection
