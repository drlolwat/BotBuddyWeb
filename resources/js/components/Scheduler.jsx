import React, {useState} from "react";

const Scheduler = ({day: defaultDay}) => {

    const [day, setDay] = useState(defaultDay ?? new Date().getDay());

    // todo: find better way to do this
    const twClassHack = (
        <>
            <div
                className="hidden sm:col-start-1 sm:col-start-2 sm:col-start-3 sm:col-start-4 sm:col-start-5 sm:col-start-6 sm:col-start-7"></div>
            <div className="hidden dark:text-red-300 dark:text-green-300 dark:text-blue-300"></div>
            <div className="hidden text-red-500 text-green-500 text-blue-500"></div>
            <div className="hidden bg-red-50 bg-green-50 bg-blue-50"></div>
            <div className="hidden dark:bg-red-800 dark:bg-green-800 dark:bg-blue-800"></div>
            <div
                className="hidden group-hover:text-red-700 group-hover:text-green-700 group-hover:text-blue-700"></div>
        </>
    )

    const events = [
        ...Window.schedule_events, // temp hack
        // {id: 1, title: 'Hello', start: 0, duration: 12, day: 1, color: 'blue'},
        // {id: 2, title: 'Test', start: 6, duration: 18, day: 2, color: 'green'},
        // {id: 3, title: '!!!', start: 24, duration: 12, day: 2, color: 'red'},
    ];

    function formatTimeRange(start, duration) {
        const startHours = Math.floor(start / 12);
        const startMinutes = (start % 12) * 5;

        const totalStartMinutes = startHours * 60 + startMinutes;
        const totalEndMinutes = totalStartMinutes + (duration / 12) * 60;
        const endHours = Math.floor(totalEndMinutes / 60);
        const endMinutes = totalEndMinutes % 60;

        const formattedStartTime = formatHoursAndMinutes(startHours, startMinutes);
        const formattedEndTime = formatHoursAndMinutes(endHours, endMinutes);

        return `${formattedStartTime} - ${formattedEndTime}`;
    }

    function formatHoursAndMinutes(hours, minutes) {
        const adjustedHours = hours % 24;
        const ampm = adjustedHours < 12 || adjustedHours === 24 ? 'AM' : 'PM';
        const adjustedHours12 = adjustedHours % 12 === 0 ? 12 : adjustedHours % 12;

        const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;

        return `${adjustedHours12}:${formattedMinutes}${ampm}`;
    }

    return (
        <div className="h-0 min-h-[768px]">
            <div className="flex h-full flex-col">
                <div className="isolate flex flex-auto flex-col overflow-auto ">
                    <div className="flex max-w-full flex-none flex-col sm:max-w-none md:max-w-full"
                         style={{width: "165%"}}>
                        <div
                            className="bg-white dark:bg-gray-800 sticky top-0 z-30 flex-none shadow ring-1 ring-black ring-opacity-5 sm:pr-8">
                            <div className="grid grid-cols-7 text-sm leading-6 text-gray-500 sm:hidden">
                                <button type="button"
                                        className={`flex flex-col items-center pb-3 pt-2 ${(day === 1) ? "dark:text-white text-black" : ""}`}
                                        onClick={() => setDay(1)}>M
                                </button>
                                <button type="button"
                                        className={`flex flex-col items-center pb-3 pt-2 ${(day === 2) ? "dark:text-white text-black" : ""}`}
                                        onClick={() => setDay(2)}>T
                                </button>
                                <button type="button"
                                        className={`flex flex-col items-center pb-3 pt-2 ${(day === 3) ? "dark:text-white text-black" : ""}`}
                                        onClick={() => setDay(3)}>W
                                </button>
                                <button type="button"
                                        className={`flex flex-col items-center pb-3 pt-2 ${(day === 4) ? "dark:text-white text-black" : ""}`}
                                        onClick={() => setDay(4)}>T
                                </button>
                                <button type="button"
                                        className={`flex flex-col items-center pb-3 pt-2 ${(day === 5) ? "dark:text-white text-black" : ""}`}
                                        onClick={() => setDay(5)}>F
                                </button>
                                <button type="button"
                                        className={`flex flex-col items-center pb-3 pt-2 ${(day === 6) ? "dark:text-white text-black" : ""}`}
                                        onClick={() => setDay(6)}>S
                                </button>
                                <button type="button"
                                        className={`flex flex-col items-center pb-3 pt-2 ${(day === 7) ? "dark:text-white text-black" : ""}`}
                                        onClick={() => setDay(7)}>S
                                </button>
                            </div>
                            <div
                                className="-mr-px hidden grid-cols-7 divide-x divide-gray-100 dark:dark:divide-gray-700 border-r border-gray-100 dark:border-gray-700 text-sm leading-6 text-gray-500 sm:grid">
                                <div className="col-end-1 w-14"></div>
                                <div className="flex items-center justify-center py-3">Mon</div>
                                <div className="flex items-center justify-center py-3">Tue</div>
                                <div className="flex items-center justify-center py-3">Wed</div>
                                <div className="flex items-center justify-center py-3">Thu</div>
                                <div className="flex items-center justify-center py-3">Fri</div>
                                <div className="flex items-center justify-center py-3">Sat</div>
                                <div className="flex items-center justify-center py-3">Sun</div>
                            </div>
                        </div>
                        <div className="flex flex-auto">
                            <div
                                className="sticky left-0 z-10 w-14 flex-none ring-1 ring-gray-100 dark:ring-gray-700"></div>
                            <div className="grid flex-auto grid-cols-1 grid-rows-1">
                                <div
                                    className="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100 dark:divide-gray-700"
                                    style={{gridTemplateRows: "repeat(48, minmax(3.5rem, 1fr))"}}>
                                    <div className="row-end-1 h-7"></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">12AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">1AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">2AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">3AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">4AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">5AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">6AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">7AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">8AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">9AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">10AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">11AM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">12PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">1PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">2PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">3PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">4PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">5PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">6PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">7PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">8PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">9PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">10PM
                                        </div>
                                    </div>
                                    <div></div>
                                    <div>
                                        <div
                                            className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-400">11PM
                                        </div>
                                    </div>
                                    <div></div>
                                </div>
                                <div
                                    className="col-start-1 col-end-2 row-start-1 hidden grid-cols-7 grid-rows-1 divide-x divide-gray-100 dark:divide-gray-700 sm:grid sm:grid-cols-7">
                                    <div className="col-start-1 row-span-full"></div>
                                    <div className="col-start-2 row-span-full"></div>
                                    <div className="col-start-3 row-span-full"></div>
                                    <div className="col-start-4 row-span-full"></div>
                                    <div className="col-start-5 row-span-full"></div>
                                    <div className="col-start-6 row-span-full"></div>
                                    <div className="col-start-7 row-span-full"></div>
                                    <div className="col-start-8 row-span-full w-8"></div>
                                </div>
                                <ol className="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-7 sm:pr-8"
                                    style={{gridTemplateRows: "1.75rem repeat(288, minmax(0px, 1fr)) auto"}}>
                                    {events.map(event => (
                                        <li className={`relative mt-px flex ${(event.day === day) ? "" : "hidden sm:block"} sm:col-start-${event.day}`}
                                            style={{gridRow: `${2 + event.start} / span ${event.duration}`}}>
                                            <a href="#"
                                               className={`group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-${event.color}-50 dark:bg-${event.color}-800 p-2 text-xs leading-5 hover:bg-${event.color}-100 dark:hover:bg-${event.color}-900`}>
                                                <p className={`order-1 font-semibold text-${event.color}-700 dark:text-${event.color}-300`}>{event.name}</p>
                                                <p className={`text-${event.color}-500 group-hover:text-${event.color}-700`}>
                                                    <time
                                                        dateTime="2022-01-12T06:00">{formatTimeRange(event.start, event.duration)}</time>
                                                </p>
                                            </a></li>
                                    ))}
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default Scheduler;
