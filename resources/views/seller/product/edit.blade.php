<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EDIT Product - Tutorial CRUD Laravel 12 @ qadrlabs.com</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>

<div class="container mx-auto mt-10 mb-10 px-10">
    <div class="grid grid-cols-8 gap-4 p-5">
        <div class="col-span-4 mt-2">
            <h1 class="text-3xl font-bold">
                EDIT PRODUCT
            </h1>
        </div>
        <div class="col-span-4">

        </div>
    </div>
    <div class="bg-white p-5 rounded shadow-sm">
        <form action="{{ route('seller.product.update',$product->id) }}" method="POST">
            
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="name">Name</label>
                <input type="text" class="
                    form-control
                    block
                    w-full
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    bg-white bg-clip-padding
                    border border-solid border-gray-300
                    rounded-full
                    transition
                    ease-in-out
                    m-0
                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none
                  "name="name" value="{{ old('name', $product->title) }}" required>

                <!-- error message untuk name -->
                @error('name')
                <div class="bg-red-400 p-2 shadow-sm rounded mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="code">Code</label>
                <input type="text" class="
                    form-control
                    block
                    w-full
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    bg-white bg-clip-padding
                    border border-solid border-gray-300
                    rounded-full
                    transition
                    ease-in-out
                    m-0
                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none
                  " name="code" value="{{ old('code', $product->slug) }}" readonly>

                <!-- error message untuk code -->
                @error('code')
                <div class="bg-red-400 p-2 shadow-sm rounded mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="price">Short Description</label>
                <input type="text" class="
                    form-control
                    block
                    w-full
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    bg-white bg-clip-padding
                    border border-solid border-gray-300
                    rounded-full
                    transition
                    ease-in-out
                    m-0
                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none
                  " name="price" value="{{ old('price', $product->short_description) }}" required>

                <!-- error message untuk price -->
                @error('price')
                <div class="bg-red-400 p-2 shadow-sm rounded mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="stock">Price</label>
                <input type="text" class="
                    form-control
                    block
                    w-full
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    bg-white bg-clip-padding
                    border border-solid border-gray-300
                    rounded-full
                    transition
                    ease-in-out
                    m-0
                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none
                  " name="stock" value="{{ old('stock', $product->price) }}" required>

                <!-- error message untuk stock -->
                @error('stock')
                <div class="bg-red-400 p-2 shadow-sm rounded mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mt-3">
                <button type="submit"
                        class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                    Save Changes
                </button>
                <a href="{{ route('seller.product.show', $product->id) }}"
                   class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out">back</a>
            </div>

        </form>

    </div>

</div>


</body>

</html>
