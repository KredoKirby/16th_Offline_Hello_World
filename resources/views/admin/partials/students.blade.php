@extends('admin.partials.layout')

@section('title','Students')
@section('content')
  <h2 style="margin:0 0 12px;">Students</h2>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>NAME</th>
          <th>EMAIL</th>
          <th>CREATED AT</th>
          <th>STATUS</th>
          <th style="width:120px">ACTION</th>
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
            <form method="POST" action="{{ route('admin.students.toggle', $row['id']) }}">
              @csrf
              <button class="btn ghost" type="submit">
                {{ $row['active'] ? 'Inactivate' : 'Activate' }}
              </button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@endsection
