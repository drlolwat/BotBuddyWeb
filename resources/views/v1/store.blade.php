<x-v1.layout>
    @if(auth()->user()->subscription)
    <div class="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
        <dt class="text-sm font-medium tracking-tight text-gray-900 dark:text-white">You have an active {{ auth()->user()->subscription->name }} subscription. Your subscription is due for renewal at {{ auth()->user()->subscription_expires_at->format('Y-m-d H:i:s T') }}. @if(config('app.stripe_manage_url') !== "")<a class="hover:underline" href="{{ config('app.stripe_manage_url') }}" target="_blank">Manage subscription</a>@endif</dt>
    </div>
    @endif
    <div class="py-6 sm:py-8">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl">Choose the right plan for you</p>
            </div>
{{--            <p class="mx-auto mt-6 max-w-2xl text-center text-lg leading-8 text-gray-600 dark:text-gray-300">Some text here</p>--}}
            <div class="mt-16 flex justify-center">
                <fieldset id="frequency-fieldset" class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200 dark:ring-0 dark:bg-white/5 dark:text-white">
                    <legend class="sr-only">Payment frequency</legend>
                    <label class="bg-blue-600 text-white dark:bg-blue-500 cursor-pointer rounded-full px-2.5 py-1">
                        <input type="radio" name="frequency" value="monthly" class="sr-only" checked>
                        <span>Monthly</span>
                    </label>
                    <label class="cursor-pointer rounded-full px-2.5 py-1">
                        <input type="radio" name="frequency" value="annually" class="sr-only">
                        <span>Annually</span>
                    </label>
                </fieldset>
            </div>
            <div class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <div class="rounded-3xl bg-gray-50 p-8 xl:p-10 dark:bg-gray-900 ring-1 ring-gray-200 dark:ring-white/10">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 id="tier-basic" class="text-lg font-semibold leading-8 text-gray-900 dark:text-white">{{ $subscriptions['basic-monthly']->name }}</h3>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-300" id="basic-description">{{ $subscriptions['basic-monthly']->description }}</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span id="basic-price" class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">$9.99</span>
                        <span id="basic-frequency" class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-300">/month</span>
                        <span id="basic-annual-percent-off" class="ml-1 hidden rounded-full bg-blue-600/10 dark:bg-blue-500 px-2.5 py-1 text-xs font-semibold text-blue-600 dark:text-white">20% OFF</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-500 hidden" id="basic-annual-price"></p>
                    <a href="{{ route('store.checkout', 'basic-monthly') }}" id="basic-link" class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 text-blue-600 ring-1 ring-inset ring-blue-200 hover:ring-blue-300 dark:ring-0 dark:hover:ring-0 dark:bg-white/10 dark:text-white dark:hover:bg-white/20 dark:focus-visible:outline-white">Buy plan</a>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 dark:text-gray-300 xl:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            1 agent
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited clients
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            5 workflows
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Real time account statistics
                        </li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-white p-8 xl:p-10 dark:bg-white/5 ring-2 ring-blue-500">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 id="tier-essential" class="text-lg font-semibold leading-8 text-blue-600 dark:text-white">{{ $subscriptions['essential-monthly']->name }}</h3>
                        <p class="rounded-full bg-blue-600/10 dark:bg-blue-500 px-2.5 py-1 text-xs font-semibold leading-5 text-blue-600 dark:text-white">Most popular</p>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-300" id="essential-description">{{ $subscriptions['essential-monthly']->description }}</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span id="essential-price" class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">$19.99</span>
                        <span id="essential-frequency" class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-300">/month</span>
                        <span id="essential-annual-percent-off" class="ml-1 hidden rounded-full bg-blue-600/10 dark:bg-blue-500 px-2.5 py-1 text-xs font-semibold text-blue-600 dark:text-white">20% OFF</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-500 hidden" id="essential-annual-price"></p>
                    <a href="{{ route('store.checkout', 'essential-monthly') }}" id="essential-link" class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 bg-blue-500 text-white shadow-sm hover:bg-blue-400 focus-visible:outline-blue-500">Buy plan</a>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 dark:text-gray-300 xl:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            3 agents
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited clients
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            15 workflows
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Real time account statistics
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Automatic temp/perm ban detection
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Account stat goals via workflows
                        </li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-gray-50 p-8 xl:p-10 dark:bg-gray-900 ring-1 ring-gray-200 dark:ring-white/10">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 id="tier-farm" class="text-lg font-semibold leading-8 text-gray-900 dark:text-white">{{ $subscriptions['farm-monthly']->name }}</h3>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-300" id="farm-description">{{ $subscriptions['farm-monthly']->description }}</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span id="farm-price" class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">$39.99</span>
                        <span id="farm-frequency" class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-300">/month</span>
                        <span id="farm-annual-percent-off" class="ml-1 hidden rounded-full bg-blue-600/10 dark:bg-blue-500 px-2.5 py-1 text-xs font-semibold text-blue-600 dark:text-white">20% OFF</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-500 hidden" id="farm-annual-price"></p>
                    <a href="{{ route('store.checkout', 'farm-monthly') }}" id="farm-link" class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 text-blue-600 ring-1 ring-inset ring-blue-200 hover:ring-blue-300 dark:ring-0 dark:hover:ring-0 dark:bg-white/10 dark:text-white dark:hover:bg-white/20 dark:focus-visible:outline-white">Buy plan</a>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 dark:text-gray-300 xl:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited agents
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited clients
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited workflows
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Real time account statistics
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Automatic temp/perm ban detection
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Account stat goals via workflows
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-blue-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Early access to new features
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        function getSelectedFrequency() {
            const frequencyRadios = document.querySelectorAll('#frequency-fieldset input[name="frequency"]');

            for (let radio of frequencyRadios) {
                if (radio.checked) {
                    return radio.value;
                }
            }

            return null;
        }

        const pricing = {
            basic: {
                monthly: {
                    price: 9.99,
                    name: '{{ $subscriptions['basic-monthly']->slug }}',
                    description: '{{ $subscriptions['basic-monthly']->description }}',
                },
                annually: {
                    price: 7.99,
                    name: '{{ $subscriptions['basic-annually']->slug }}',
                    description: '{{ $subscriptions['basic-annually']->description }}',
                }
            },
            essential: {
                monthly: {
                    price: 19.99,
                    name: '{{ $subscriptions['essential-monthly']->slug }}',
                    description: '{{ $subscriptions['essential-monthly']->description }}',
                },
                annually: {
                    price: 15.99,
                    name: '{{ $subscriptions['essential-annually']->slug }}',
                    description: '{{ $subscriptions['essential-annually']->description }}',
                }
            },
            farm: {
                monthly: {
                    price: 39.99,
                    name: '{{ $subscriptions['farm-monthly']->slug }}',
                    description: '{{ $subscriptions['farm-monthly']->description }}',
                },
                annually: {
                    price: 31.99,
                    name: '{{ $subscriptions['farm-annually']->slug }}',
                    description: '{{ $subscriptions['farm-annually']->description }}',
                }
            },
        };

        function updatePricing(e) {
            const selectedFrequency = getSelectedFrequency();
            const checkedRadio = e.target;
            const frequencyRadios = document.querySelectorAll('#frequency-fieldset input[name="frequency"]');

            for (let radio of frequencyRadios) {
                if (radio !== checkedRadio) {
                    radio.parentElement.classList.remove('bg-blue-600', 'text-white', 'dark:bg-blue-500');
                    radio.parentElement.classList.add('text-gray-500', 'dark:text-white');
                }
            }

            checkedRadio.parentElement.classList.remove('text-gray-500', 'dark:text-white');
            checkedRadio.parentElement.classList.add('bg-blue-600', 'text-white', 'dark:bg-blue-500');

            for (let tier in pricing) {
                document.getElementById(`tier-${tier}`).textContent = tier.charAt(0).toUpperCase() + tier.slice(1);
                document.getElementById(`${tier}-price`).textContent = '$' + pricing[tier][selectedFrequency].price;
                document.getElementById(`${tier}-description`).textContent = pricing[tier][selectedFrequency].description;
                document.getElementById(`${tier}-frequency`).textContent = `/month`;
                document.getElementById(`${tier}-link`).href = `/store/${pricing[tier][selectedFrequency].name}`;

                const annual = document.getElementById(`${tier}-annual-price`);
                const annualPercentOff = document.getElementById(`${tier}-annual-percent-off`);
                if (selectedFrequency === 'annually') {
                    annual.innerText = `Billed $${pricing[tier][selectedFrequency].price*12} annually`;
                    annual.classList.remove('hidden');
                    annualPercentOff.innerText = `Save $${pricing[tier].monthly.price*12 - pricing[tier].annually.price*12}/year`;
                    annualPercentOff.classList.remove('hidden');
                } else {
                    annual.classList.add('hidden');
                    annualPercentOff.classList.add('hidden');
                    annual.innerText = '';
                }
            }
        }

        document.getElementById('frequency-fieldset').addEventListener('change', updatePricing);
    </script>
</x-v1.layout>
