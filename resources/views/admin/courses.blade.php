@extends('layouts.admin')

@section('title','Courses')
@section('content')
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px">
    <h2 style="margin:0">Courses</h2>
    <a class="btn dark" href="{{ route('admin.courses.create') }}">+ Add a course </a>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>NAME</th>
          <th>STATUS</th>
          <th style="width:140px">ACTION</th>
        </tr>
      </thead>
      <tbody>
      @foreach($items as $row)
        <tr>
          <td>
            {{ $row['name'] }}
          </td>
          <td>
            <span class="dot {{ $row['active'] ? 'green':'gray' }}"></span>
            {{ $row['active'] ? 'Active':'Inactive' }}
          </td>
          <td>
            <form method="POST" action="{{ route('admin.courses.toggle', $row['id']) }}">
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
