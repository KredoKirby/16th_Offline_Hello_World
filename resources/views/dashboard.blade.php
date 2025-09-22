<h1>Dashboard</h1>
<p>ようこそ、{{ auth()->user()->name }} さん</p>
<form method="POST" action="{{ route('logout') }}">
  @csrf
  <button type="submit">ログアウト</button>
</form>
