<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Detail {{ $product->title }} - Tutorial CRUD Laravel 12 @ qadrlabs.com</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>

<div class="container mx-auto mt-10 mb-10 px-10">
    <div class="grid grid-cols-8 gap-4 mb-4 p-5">
        <div class="col-span-4 mt-2">
            <h1 class="text-3xl font-bold">
                Product Detail {{ $product->title }}
            </h1>

        </div>
    </div>
    <div class="bg-white p-5 rounded shadow-sm">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Product Name
                    </th>
                    <td class="px-6 py-4">
                        {{ $product->title}}
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Product Code
                    </th>
                    <td class="px-6 py-4">
                        {{ $product->slug }}
                    </td>
                </tr>

                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Stock
                    </th>
                    <td class="px-6 py-4">
                        {{ $product->stock }}
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Price
                    </th>
                    <td class="px-6 py-4">
                        {{ $product->price }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


    </div>

    <a href="{{ route('seller.product.index') }}"
       class="mt-3 inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs leading-tight uppercase rounded-full ">back</a>
    <a href="{{ route('seller.product.edit', $product->id) }}"
       class="inline-block px-6 py-2.5 bg-blue-400 text-white font-medium text-xs leading-tight uppercase rounded-full"
       id="edit-product-btn">Edit Product</a>

</div>

</body>

</html>
