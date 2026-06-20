@extends('errors.layout')

@section('title', 'Waking up')
@section('code', 'Error 503 · Please wait')
@section('heading', 'The site is waking up')
@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
@endsection
@section('message', 'This free server goes to sleep when idle to save resources. It only takes a few seconds to start. Hang tight — we will reload automatically.')

@section('extra')
    <div class="spinner"></div>
    <div class="count">Retrying in <b><span id="count">10</span></b> seconds…</div>
@endsection

@section('actions')
    <button onclick="location.reload()" class="btn btn-primary">Try now</button>
@endsection

@section('scripts')
<script>
    var n = 10;
    var el = document.getElementById('count');
    var t = setInterval(function(){
        n--;
        if (el) el.textContent = n;
        if (n <= 0){ clearInterval(t); location.reload(); }
    }, 1000);
</script>
@endsection
