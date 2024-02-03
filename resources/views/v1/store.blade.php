<x-v1.layout>
    <div class="bg-white dark:bg-gray-900 py-6 sm:py-8">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600 dark:text-indigo-400">Store</h2>
                <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl">Choose the right plan for you</p>
            </div>
            <p class="mx-auto mt-6 max-w-2xl text-center text-lg leading-8 text-gray-600 dark:text-gray-300">Some text here</p>
            <div class="mt-16 flex justify-center">
                <fieldset id="frequency-fieldset" class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200 dark:ring-0 dark:bg-white/5 dark:text-white">
                    <legend class="sr-only">Payment frequency</legend>
                    <label class="bg-indigo-600 text-white dark:bg-indigo-500 cursor-pointer rounded-full px-2.5 py-1">
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
                <div class="rounded-3xl p-8 xl:p-10 ring-1 ring-gray-200 dark:ring-white/10">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 id="tier-basic" class="text-lg font-semibold leading-8 text-gray-900 dark:text-white">Basic</h3>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-300">Some description here</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span id="basic-price" class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">$10</span>
                        <span id="basic-frequency" class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-300">/month</span>
                    </p>
                    <a href="{{ route('store.checkout', 'basic-monthly') }}" id="basic-link" class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300 dark:ring-0 dark:hover:ring-0 dark:bg-white/10 dark:text-white dark:hover:bg-white/20 dark:focus-visible:outline-white">Buy plan</a>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 dark:text-gray-300 xl:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            1 agent
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited clients
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Basic analytics
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            24-hour support response time
                        </li>
                    </ul>
                </div>
                <div class="rounded-3xl p-8 xl:p-10 bg-white/5 ring-2 ring-indigo-500">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 id="tier-essential" class="text-lg font-semibold leading-8 text-indigo-600 dark:text-white">Essential</h3>
                        <p class="rounded-full bg-indigo-600/10 dark:bg-indigo-500 px-2.5 py-1 text-xs font-semibold leading-5 text-indigo-600 dark:text-white">Most popular</p>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-300">Some description here</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span id="essential-price" class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">$25</span>
                        <span id="essential-frequency" class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-300">/month</span>
                    </p>
                    <a href="{{ route('store.checkout', 'essential-monthly') }}" id="essential-link" class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 bg-indigo-500 text-white shadow-sm hover:bg-indigo-400 focus-visible:outline-indigo-500">Buy plan</a>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 dark:text-gray-300 xl:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            3 agents
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited clients
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Advanced analytics
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            24-hour support response time
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Something else
                        </li>
                    </ul>
                </div>
                <div class="rounded-3xl p-8 xl:p-10 ring-1 ring-gray-200 dark:ring-white/10">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 id="tier-growth" class="text-lg font-semibold leading-8 text-gray-900 dark:text-white">Growth</h3>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-300">Some description here</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span id="growth-price" class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">$50</span>
                        <span id="growth-frequency" class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-300">/month</span>
                    </p>
                    <a href="{{ route('store.checkout', 'growth-monthly') }}" id="growth-link" class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300 dark:ring-0 dark:hover:ring-0 dark:bg-white/10 dark:text-white dark:hover:bg-white/20 dark:focus-visible:outline-white">Buy plan</a>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 dark:text-gray-300 xl:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited agents
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Unlimited clients
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Advanced analytics
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            1-hour, dedicated support response time
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Something else
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-indigo-600 dark:text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            Something else
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
                    price: 10,
                    name: 'basic-monthly',
                },
                annually: {
                    price: 100,
                    name: 'basic-annually',
                }
            },
            essential: {
                monthly: {
                    price: 25,
                    name: 'essential-monthly',
                },
                annually: {
                    price: 250,
                    name: 'essential-annually',
                }
            },
            growth: {
                monthly: {
                    price: 50,
                    name: 'growth-monthly',
                },
                annually: {
                    price: 500,
                    name: 'growth-annually',
                }
            },
        };

        function updatePricing(e) {
            const selectedFrequency = getSelectedFrequency();
            const checkedRadio = e.target;
            const frequencyRadios = document.querySelectorAll('#frequency-fieldset input[name="frequency"]');

            for (let radio of frequencyRadios) {
                if (radio !== checkedRadio) {
                    radio.parentElement.classList.remove('bg-indigo-600', 'text-white', 'dark:bg-indigo-500');
                    radio.parentElement.classList.add('text-gray-500', 'dark:text-white');
                }
            }

            checkedRadio.parentElement.classList.remove('text-gray-500', 'dark:text-white');
            checkedRadio.parentElement.classList.add('bg-indigo-600', 'text-white', 'dark:bg-indigo-500');

            for (let tier in pricing) {
                document.getElementById(`tier-${tier}`).textContent = tier.charAt(0).toUpperCase() + tier.slice(1);
                document.getElementById(`${tier}-price`).textContent = '$' + pricing[tier][selectedFrequency].price;
                if (selectedFrequency === 'annually') {
                    document.getElementById(`${tier}-frequency`).textContent = `/year`;
                } else {
                    document.getElementById(`${tier}-frequency`).textContent = `/month`;
                }
                document.getElementById(`${tier}-link`).href = `/store/${pricing[tier][selectedFrequency].name}`;
            }
        }

        document.getElementById('frequency-fieldset').addEventListener('change', updatePricing);
    </script>
</x-v1.layout>
