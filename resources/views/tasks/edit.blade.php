<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl">Edit Task</h2>
    </x-slot>

    <form method="POST" action="{{ route('tasks.update', $task) }}">
    @method('PUT')
    @include('tasks._form', ['task' => $task])
    </form>

    <div class="mt-4">
    <a href="{{ route('tasks.index') }}" class="underline text-blue-600">Back</a>
    </div>
</x-app-layout>
