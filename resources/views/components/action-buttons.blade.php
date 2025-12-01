@props(['editUrl' => '#', 'deleteUrl' => '#', 'confirm' => 'Yakin ingin menghapus item ini?'])
<div class="flex gap-2 justify-end items-center">
    <a href="{{ $editUrl }}"
        class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 transition font-medium text-sm">✏️
        Edit</a>
    <form action="{{ $deleteUrl }}" method="POST" onsubmit="return confirm('{{ $confirm }}');" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition font-medium text-sm">🗑️
            Hapus</button>
    </form>
</div>