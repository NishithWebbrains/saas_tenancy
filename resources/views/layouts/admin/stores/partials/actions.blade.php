<a href="{{ route('stores.edit', $store) }}" class="btn btn-sm btn-warning">Edit</a>
<form action="{{ route('stores.destroy', $store) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger"
        onclick="return confirm('Delete this store?')">Delete</button>
</form>