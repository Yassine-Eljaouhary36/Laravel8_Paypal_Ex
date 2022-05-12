<x-app-layout>
    <div class="container mx-auto flex justify-center mt-6 ">
        <div class="w-1/3">
            @if ($errors->any())
            <div class="px-4 py-3 bg-red-200 rounded-lg mb-3" style="border: 2px solid rgb(220 38 38)">	
            @foreach ($errors->all() as $error)
                <strong>-</strong>{{ $error }}<br>
            @endforeach
            </div>
            @endif
            @if ($message = Session::get('failed'))
                <div class="px-4 py-3 bg-red-200 rounded-lg mb-3" style="border: 2px solid rgb(220 38 38)">	
                <strong>{{ $message }}</strong>
                </div>
            @endif
            <form action="{{route('service.store')}}"  method="post">
                @csrf
            <label class="block">
                <span class="text-gray-700">Title</span>
                <input type="text" name="title" value="{{old('title')}}" class="form-input px-4 py-3 rounded-lg mt-1 block w-full">
            </label>
            <label class="block mt-2">
                <span class="text-gray-700">Content</span>
                <textarea class="mt-1 block w-full rounded-lg" name="content" value="{{old('content')}}" rows="3"></textarea>
            </label>
            <div class="block">
                <div class="mt-2">
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="premium" :value="old('premium')" class="form-checkbox rounded text-pink-500" />
                            <span class="ml-2">Premium Service ?</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="block mt-5">
                <x-button id="submit-button" class="rounded-full">Save The Service</x-button>
            </div>
            </form>
        </div>
    </div>
    @section('extra-js') 
    @endsection
</x-app-layout>