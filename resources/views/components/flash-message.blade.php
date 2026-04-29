{{-- FLASH MESSAGES SYSTEM --}}

{{-- ✅ SUCCESS --}}
@if (session()->has('success'))
        <div x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            class="fixed top-6 right-6 z-50 w-auto max-w-sm animate-fade-in-down">

        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-r-4 border-emerald-500 rounded-xl shadow-lg p-4 backdrop-blur-sm">

            <div class="flex items-center gap-3">

                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="text-emerald-800 dark:text-emerald-200 font-medium">
                    {{ session('success') }}
                </div>

            </div>

        </div>
    </div>
@endif


{{-- ❌ ERROR --}}
@if (session()->has('error'))
        <div x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            class="fixed top-6 right-6 z-50 w-auto max-w-sm animate-fade-in-down">

        <div class="bg-red-50 dark:bg-red-900/30 border-r-4 border-red-500 rounded-xl shadow-lg p-4 backdrop-blur-sm">

            <div class="flex items-center gap-3">

                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="text-red-800 dark:text-red-200 font-medium">
                    {{ session('error') }}
                </div>

            </div>

        </div>
    </div>
@endif


{{-- ⚠️ WARNING --}}
@if (session()->has('warning'))
        <div x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            class="fixed top-6 right-6 z-50 w-auto max-w-sm animate-fade-in-down">

        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-r-4 border-yellow-500 rounded-xl shadow-lg p-4 backdrop-blur-sm">

            <div class="flex items-center gap-3">

                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M8.257 3.099c.366-.446.957-.446 1.323 0l6.518 7.94c.38.463.05 1.146-.662 1.146H2.401c-.712 0-1.042-.683-.662-1.146l6.518-7.94zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="text-yellow-800 dark:text-yellow-200 font-medium">
                    {{ session('warning') }}
                </div>

            </div>

        </div>
    </div>
@endif
