<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Testimonial') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-10 shadow-sm sm:rounded-lg">
                
                <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mt-4">
                        <x-input-label for="project_client" :value="__('Project Client')" />
                        <select name="project_client_id" id="project_client_id" class="py-3 rounded-lg pl-3 w-full border border-slate-300">
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('project_client_id', $testimonial->project_client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('project_client_id')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="message" :value="__('Message')" />
                        <textarea name="message" id="message" cols="30" rows="5" class="border border-slate-300 rounded-xl w-full">{{ old('message', $testimonial->message) }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="thumbnail" :value="__('Thumbnail')" />
                        @if ($testimonial->thumbnail)
                            <img src="{{ Storage::url($testimonial->thumbnail) }}" alt="Thumbnail" class="rounded-2xl object-cover w-[90px] h-[90px]">
                        @endif
                        <x-text-input id="thumbnail" class="block mt-1 w-full" type="file" name="thumbnail" />
                        <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                    </div> 

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="font-bold py-4 px-6 bg-indigo-700 text-white rounded-full">
                            Update Testimonial
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
