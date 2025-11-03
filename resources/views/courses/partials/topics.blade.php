<div class="bg-white border-top p-3">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Topic</th>
                <th>Status</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($course->topics as $topic)
            <tr>
                <td>{{ $topic->name }}</td>
                <td>
                    <span class="status-dot {{ $topic->is_active ? 'bg-success' : 'bg-secondary' }}"></span>
                    {{ $topic->is_active ? 'Active' : 'Inactive' }}
                </td>
                <td class="text-end">
                    <form method="POST" action="{{ route('admin.courses.toggleTopic', $topic) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm {{ $topic->is_active ? 'btn-secondary' : 'btn-success' }}">
                            {{ $topic->is_active ? 'Inactivate' : 'Activate' }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
