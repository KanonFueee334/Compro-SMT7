@extends('layout.app')
@section('title', 'Daftar Penerimaan')
@section('content')
<script>
    window.location.href = "{{ route('admin.penerimaan.index') }}";
</script>
<div class="container">
    <h2>Daftar Penerimaan</h2>
    <p>Redirecting to Penerimaan page...</p>
</div>
@endsection 