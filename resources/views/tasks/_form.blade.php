@csrf
<div class="space-y-4">
    <div>
    <label class="block text-sm font-medium">Title</label>
    <input name="title"
            value="{{ old('title', $task->title ?? '') }}"
            maxlength="100" required
            class="border p-2 w-full rounded">
    @error('title')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    <div class="flex items-center gap-2">
    {{-- 未チェック対策：常にキーを送る --}}
    <input type="hidden" name="is_done" value="0">
    <input id="is_done" type="checkbox" name="is_done" value="1"
            @checked(old('is_done', $task->is_done ?? false))>
    <label for="is_done">Done</label>
    </div>

    <button class="px-4 py-2 bg-blue-600 text-white rounded">
    {{ isset($task) ? 'Update' : 'Create' }}
    </button>
</div>
