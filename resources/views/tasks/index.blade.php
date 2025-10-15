<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl">Tasks</h2>
    </x-slot>

    {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

    <div class="flex items-center justify-between mb-4">
    <a href="{{ route('tasks.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ New</a>
    </div>

    <ul class="list-disc pl-6">
    @forelse($tasks as $task)
        <li class="py-1">
        <a class="underline" href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
        </li>
    @empty
        <li>No tasks.</li>
    @endforelse
    </ul>

    <div class="mt-4">
    {{ $tasks->links() }}
    </div>
</x-app-layout>
