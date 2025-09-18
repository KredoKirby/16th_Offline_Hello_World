@extends('layouts.admin')

@section('title','Teachers')
@section('content')
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px">
    <h2 style="margin:0">Teachers</h2>
    <a class="btn dark" href="{{ route('admin.teachers.add.form') }}">+ Add</a>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>NAME</th>
          <th>EMAIL</th>
          <th>CREATED AT</th>
          <th>STATUS</th>
          <th style="width:160px">ACTION</th>
        </tr>
      </thead>
      <tbody>
      @foreach($items as $row)
        <tr>
          <td>
            <img class="avatar" src="{{ $row['avatar'] }}" alt="">
            {{ $row['name'] }}
          </td>
          <td>{{ $row['email'] }}</td>
          <td>{{ $row['created_at'] }}</td>
          <td>
            <span class="dot {{ $row['active'] ? 'green':'gray' }}"></span>
            {{ $row['active'] ? 'Active':'Inactive' }}
          </td>
          <td>
            <div style="display:flex; gap:8px">
              <a class="btn ghost" href="#"></a>
              <form method="POST" action="{{ route('admin.teachers.toggle', $row['id']) }}">
                @csrf
                <button class="btn ghost" type="submit">
                  {{ $row['active'] ? 'Inactivate' : 'Activate' }}
                </button>
              </form>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@endsection
