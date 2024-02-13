<x-guest-layout>
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-ash-200 dark:bg-dots-lighter dark:bg-gravel-950 selection:bg-maize-400 selection:text-gravel-900 text-gravel-700 dark:text-ash-500">
        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gravel-900 hover:text-gravel-300 dark:text-ash-500 dark:hover:text-ash-200 focus:outline focus:outline-2 focus:rounded-sm focus:outline-maize-900 dark:focus:outline-maize-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gravel-900 hover:text-gravel-300 dark:text-ash-500 dark:hover:text-ash-200 focus:outline focus:outline-2 focus:rounded-sm focus:outline-maize-900 dark:focus:outline-maize-600">Admin</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gravel-900 hover:text-gravel-300 dark:text-ash-500 dark:hover:text-ash-200 focus:outline focus:outline-2 focus:rounded-sm focus:outline-maize-900 dark:focus:outline-maize-600">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8">


            <div class="mt-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 lg:place-items-stretch gap-6 lg:gap-8">
                    <div class="flex flex-col gap-8">
                        <div class="scale-100 p-6 bg-white dark:bg-gravel-650/50 dark:bg-gradient-to-bl from-gravel-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-ash-400/5 rounded-lg shadow-2xl shadow-ash-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-transform ease-out duration-250 focus:outline focus:outline-2 focus:outline-maize-900 dark:focus:outline-maize-600">
                            <div>
                                <div class="h-16 w-16 bg-maize-300 dark:bg-maize-700/20 flex items-center justify-center rounded-full">
                                    <svg class="w-7 h-7 fill-maize-950 dark:fill-maize-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 11a1 1 0 0 0-1 1v3a1 1 0 0 0 2 0v-3a1 1 0 0 0-1-1Zm0-3a1 1 0 1 0 1 1 1 1 0 0 0-1-1Zm0-6A10 10 0 0 0 2 12a9.89 9.89 0 0 0 2.26 6.33l-2 2a1 1 0 0 0-.21 1.09A1 1 0 0 0 3 22h9a10 10 0 0 0 0-20Zm0 18H5.41l.93-.93a1 1 0 0 0 .3-.71 1 1 0 0 0-.3-.7A8 8 0 1 1 12 20Z"/>
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-3xl leading-6 font-semibold text-gravel-900 dark:text-ash-300">
                                    Checked-In<br>
                                    <span class="text-xxs leading-3 uppercase">{{$registration->name}}</span>
                                </h2>

                                <p class="mt-4 text-gravel-700 dark:text-ash-500 text-sm leading-relaxed">
                                    Registration ID: {{$registration->registration_id}}
                                </p>
                            </div>

                        </div>
                        <div class="hidden lg:block p-6 bg-white dark:bg-gravel-650/50 dark:bg-gradient-to-bl from-gravel-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-ash-400/5 rounded-lg shadow-2xl shadow-ash-500/20 dark:shadow-none">
                            <div>
                                <div class="h-16 w-16 bg-maize-300 dark:bg-maize-700/20 flex items-center justify-center rounded-full">
                                    <svg class="w-7 h-7 fill-maize-950 dark:fill-maize-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M11.29 15.29a1.58 1.58 0 0 0-.12.15.76.76 0 0 0-.09.18.64.64 0 0 0-.06.18 1.36 1.36 0 0 0 0 .2.84.84 0 0 0 .08.38.9.9 0 0 0 .54.54.94.94 0 0 0 .76 0 .9.9 0 0 0 .54-.54A1 1 0 0 0 13 16a1 1 0 0 0-.29-.71 1 1 0 0 0-1.42 0ZM12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm0-13a3 3 0 0 0-2.6 1.5 1 1 0 1 0 1.73 1A1 1 0 0 1 12 9a1 1 0 0 1 0 2 1 1 0 0 0-1 1v1a1 1 0 0 0 2 0v-.18A3 3 0 0 0 12 7Z"/>
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gravel-900 dark:text-ash-300">Contact</h2>

                                <p class="mt-4 text-gravel-900 dark:text-ash-500 text-sm leading-relaxed">
                                    If you need assistance after you've registered, contact us at:
                                    <ul class="mt-5 flex flex-col gap-y-3">
                                        <li class="flex align-center gap-x-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-maize-950 dark:fill-maize-900 w-6">
                                                <path d="M19 4H5a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3ZM5 6h14a1 1 0 0 1 1 1l-8 4.88L4 7a1 1 0 0 1 1-1Zm15 11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.28l7.48 4.57a1 1 0 0 0 1 0L20 9.28Z"/>
                                            </svg>
                                            <a class="text-gravel-700 hover:text-ash-500 dark:text-ash-300 dark:hover:text-ash-200 transition-colors ease-out duration-250 focus:outline focus:outline-2 focus:outline-maize-900 dark:focus:outline-maize-600 dark:focus:text-ash-200" href="mailto:info@revivalmovementusa.org">info@<wbr>revivalmovementusa.org</a>
                                        </li>
                                    </ul>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white dark:bg-gravel-650/50 dark:bg-gradient-to-bl from-gravel-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-ash-400/5 rounded-lg shadow-2xl shadow-ash-500/20 dark:shadow-none md:row-span-2">
                        <div>
                            <h2 class="mt-2 text-xl font-semibold text-gravel-900 dark:text-ash-300">Check-In Details</h2>

                            <p class="my-4 text-gravel-900 dark:text-ash-500 text-sm leading-relaxed">
                                This is your confirmation that you have checked in for the upcoming event. Below are the details of your check-in:
                            </p>

                            <p>
                                <span class="font-semibold">Confirmation Number:</span> <span class="text-maize-900 dark:text-maize-600">{{ $registration->registration_id }}</span>
                            </p>

                            <p>
                                <span class="font-semibold">Customer Name:</span> <span class="text-maize-900 dark:text-maize-600">{{ $registration->customer->fullName() }}</span>
                            </p>

                            <p>
                                <span class="font-semibold">Number of Guests:</span> <span class="text-maize-900 dark:text-maize-600">{{ $registration->guests }}</span>
                            </p>

                            <p>
                                <span class="font-semibold">Checked in at:</span> <span class="text-maize-900 dark:text-maize-600">{{ Carbon\Carbon::parse($registration->checkedin_at, 'America/New_York')->format('F j, Y h:ia') }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="lg:hidden p-6 bg-white dark:bg-gravel-650/50 dark:bg-gradient-to-bl from-gravel-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-ash-400/5 rounded-lg shadow-2xl shadow-ash-500/20 dark:shadow-none">
                        <div>
                            <div class="h-16 w-16 bg-maize-300 dark:bg-maize-700/20 flex items-center justify-center rounded-full">
                                <svg class="w-7 h-7 fill-maize-950 dark:fill-maize-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M11.29 15.29a1.58 1.58 0 0 0-.12.15.76.76 0 0 0-.09.18.64.64 0 0 0-.06.18 1.36 1.36 0 0 0 0 .2.84.84 0 0 0 .08.38.9.9 0 0 0 .54.54.94.94 0 0 0 .76 0 .9.9 0 0 0 .54-.54A1 1 0 0 0 13 16a1 1 0 0 0-.29-.71 1 1 0 0 0-1.42 0ZM12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm0-13a3 3 0 0 0-2.6 1.5 1 1 0 1 0 1.73 1A1 1 0 0 1 12 9a1 1 0 0 1 0 2 1 1 0 0 0-1 1v1a1 1 0 0 0 2 0v-.18A3 3 0 0 0 12 7Z"/>
                                </svg>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gravel-900 dark:text-ash-300">Contact</h2>

                            <p class="mt-4 text-gravel-900 dark:text-ash-500 text-sm leading-relaxed">
                                If you need help filling out this form, or need assistance after you've registered, contact us at:
                                <ul class="mt-5 flex flex-col gap-y-3">
                                    <li class="flex align-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-maize-950 dark:fill-maize-900 w-6">
                                            <path d="M19 4H5a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3ZM5 6h14a1 1 0 0 1 1 1l-8 4.88L4 7a1 1 0 0 1 1-1Zm15 11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.28l7.48 4.57a1 1 0 0 0 1 0L20 9.28Z"/>
                                        </svg>
                                        <a class="text-gravel-700 hover:text-ash-500 dark:text-ash-300 dark:hover:text-ash-200 transition-colors ease-out duration-250 focus:outline focus:outline-2 focus:outline-maize-900 dark:focus:outline-maize-600 dark:focus:text-ash-200" href="mailto:info@revivalmovementusa.org">info@<wbr>revivalmovementusa.org</a>
                                    </li>
                                </ul>
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gravel-300 dark:text-ash-600 sm:text-left">
                    <div class="flex items-center gap-4">
                        <span>App by <a target="_blank" href="https://marloncastillo.dev" class="hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-maize-900 dark:focus:outline-maize-600">Marlon Castillo</a></span>
                    </div>
                </div>

                <div class="ml-4 text-sm text-gravel-300 dark:text-ash-600 sm:text-right sm:ml-0">&copy; {{ date('Y') }} <a target="_blank" href="https://revivalmovementusa.org" class="hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-maize-900 dark:focus:outline-maize-600">Revival Movement</a></div>
            </div>
        </div>
    </div>
</x-guest-layout>
