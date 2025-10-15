<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl">New Task</h2>
    </x-slot>

    <form method="POST" action="{{ route('tasks.store') }}">
    @include('tasks._form')
    </form>

    <div class="mt-4">
    <a href="{{ route('tasks.index') }}" class="underline text-blue-600">Back</a>
    </div>
</x-app-layout>
